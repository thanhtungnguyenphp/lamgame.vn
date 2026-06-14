import { Server } from "@modelcontextprotocol/sdk/server/index.js";
import { StdioServerTransport } from "@modelcontextprotocol/sdk/server/stdio.js";
import { CallToolRequestSchema, ListToolsRequestSchema } from "@modelcontextprotocol/sdk/types.js";
import { execSync } from "child_process";

const PROJECT_DIR = "/data/www/lamgame.vn";
const DOCKER_PHP = "docker exec lg-php";

function runArtisan(cmd) {
  try {
    return execSync(`${DOCKER_PHP} php /var/www/html/artisan ${cmd}`, {
      timeout: 60000,
      encoding: "utf-8",
    }).trim();
  } catch (e) {
    return `Error: ${e.message}`;
  }
}

function runCurl(url) {
  try {
    return execSync(`curl -sI "${url}" -o /dev/null -w "%{http_code}|%{redirect_url}" --max-time 10`, {
      encoding: "utf-8",
    }).trim();
  } catch (e) {
    return `Error: ${e.message}`;
  }
}

const server = new Server(
  { name: "mcp-seo-lamgame", version: "1.0.0" },
  { capabilities: { tools: {} } }
);

server.setRequestHandler(ListToolsRequestSchema, async () => ({
  tools: [
    {
      name: "seo_generate_sitemap",
      description: "Generate/refresh all XML sitemaps",
      inputSchema: { type: "object", properties: {} },
    },
    {
      name: "seo_push_indexnow",
      description: "Push URLs to IndexNow (Bing/Yandex) for fast indexing",
      inputSchema: {
        type: "object",
        properties: { limit: { type: "number", default: 50, description: "Max URLs per content type" } },
      },
    },
    {
      name: "seo_auto_index",
      description: "Auto-detect new content and push to search engines",
      inputSchema: {
        type: "object",
        properties: { force: { type: "boolean", default: false, description: "Push all URLs regardless of last run" } },
      },
    },
    {
      name: "seo_check_url",
      description: "Check HTTP status, redirect, and headers for a URL",
      inputSchema: {
        type: "object",
        properties: { url: { type: "string", description: "URL to check" } },
        required: ["url"],
      },
    },
    {
      name: "seo_check_robots",
      description: "Check if a URL is blocked by robots.txt",
      inputSchema: {
        type: "object",
        properties: { path: { type: "string", description: "URL path to check (e.g. /blog/my-post)" } },
        required: ["path"],
      },
    },
    {
      name: "seo_health_report",
      description: "Full SEO health report: sitemap status, indexing stats, canonical issues",
      inputSchema: { type: "object", properties: {} },
    },
  ],
}));

server.setRequestHandler(CallToolRequestSchema, async (request) => {
  const { name, arguments: args } = request.params;

  switch (name) {
    case "seo_generate_sitemap": {
      const output = runArtisan("sitemap:generate");
      return { content: [{ type: "text", text: output }] };
    }

    case "seo_push_indexnow": {
      const limit = args?.limit || 50;
      const output = runArtisan(`google:push-index --type=indexnow --limit=${limit}`);
      return { content: [{ type: "text", text: output }] };
    }

    case "seo_auto_index": {
      const flag = args?.force ? " --force" : "";
      const output = runArtisan(`seo:auto-index${flag}`);
      return { content: [{ type: "text", text: output }] };
    }

    case "seo_check_url": {
      const url = args.url;
      const result = runCurl(url);
      const [status, redirect] = result.split("|");
      const headers = execSync(`curl -sI "${url}" --max-time 10`, { encoding: "utf-8" });
      const robotsTag = headers.match(/x-robots-tag:\s*(.+)/i)?.[1] || "none";
      const canonical = status === "200" ? "Check HTML for <link rel=canonical>" : `Redirect → ${redirect}`;
      return {
        content: [{
          type: "text",
          text: `URL: ${url}\nHTTP Status: ${status}\nX-Robots-Tag: ${robotsTag}\n${redirect ? `Redirect: ${redirect}\n` : ""}${canonical}`,
        }],
      };
    }

    case "seo_check_robots": {
      const path = args.path;
      const fs = await import("fs");
      const robotsTxt = fs.readFileSync(`${PROJECT_DIR}/public/robots.txt`, "utf-8");
      const disallowRules = robotsTxt.match(/Disallow:\s*.+/g) || [];
      const blocked = disallowRules.some((rule) => {
        const pattern = rule.replace("Disallow:", "").trim().replace("*", ".*");
        return new RegExp("^" + pattern).test(path);
      });
      return {
        content: [{ type: "text", text: `Path: ${path}\nBlocked by robots.txt: ${blocked ? "YES ❌" : "NO ✅"}` }],
      };
    }

    case "seo_health_report": {
      const fs = await import("fs");
      const lines = [];

      // Sitemap status
      const sitemapFiles = ["sitemap.xml", "sitemap-pages.xml", "sitemap-blogs.xml", "sitemap-forum.xml", "sitemap-source-game.xml", "sitemap-sellers.xml", "sitemap-landing.xml", "sitemap-jobs.xml"];
      lines.push("=== SITEMAP STATUS ===");
      for (const f of sitemapFiles) {
        try {
          const stat = fs.statSync(`${PROJECT_DIR}/public/${f}`);
          const age = Math.round((Date.now() - stat.mtimeMs) / 86400000);
          lines.push(`${f}: ${(stat.size / 1024).toFixed(1)}KB, ${age}d old ${age > 2 ? "⚠️ STALE" : "✅"}`);
        } catch {
          lines.push(`${f}: MISSING ❌`);
        }
      }

      // Content counts
      lines.push("\n=== CONTENT INDEXED ===");
      const counts = runArtisan('tinker --execute="echo json_encode([\'blogs\'=>\\App\\Models\\Blog::published()->count(),\'source_games\'=>\\DB::table(\'products as p\')->join(\'product_flat as pf\',function(\\$j){\\$j->on(\'p.id\',\'=\',\'pf.product_id\')->where(\'pf.locale\',\'=\',\'vi\');})->where(\'p.type\',\'downloadable\')->where(\'pf.status\',1)->where(\'pf.visible_individually\',1)->count(),\'forum\'=>\\DB::table(\'forum_posts\')->where(\'status\',\'published\')->count(),\'landing\'=>\\App\\Models\\LandingPage::active()->count()]);"');
      try {
        const data = JSON.parse(counts);
        lines.push(`Blogs: ${data.blogs}`);
        lines.push(`Source Games: ${data.source_games}`);
        lines.push(`Forum Posts: ${data.forum}`);
        lines.push(`Landing Pages: ${data.landing}`);
      } catch {
        lines.push(`Raw: ${counts}`);
      }

      // Recent index log
      lines.push("\n=== RECENT INDEXING ===");
      try {
        const log = fs.readFileSync(`${PROJECT_DIR}/storage/logs/seo-auto-index.log`, "utf-8");
        lines.push(log.split("\n").slice(-5).join("\n") || "No auto-index runs yet");
      } catch {
        lines.push("No auto-index log yet");
      }

      return { content: [{ type: "text", text: lines.join("\n") }] };
    }

    default:
      return { content: [{ type: "text", text: `Unknown tool: ${name}` }] };
  }
});

async function main() {
  const transport = new StdioServerTransport();
  await server.connect(transport);
}

main().catch(console.error);
