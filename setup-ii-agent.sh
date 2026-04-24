#!/bin/bash
# Setup II-Agent source code for LamGame Docker build
# Run this once before first `docker compose build ii-agent`

set -e

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
II_AGENT_DIR="${SCRIPT_DIR}/docker/ii-agent/src"

echo "=== II-Agent Setup for LamGame ==="

# Step 1: Clone II-Agent source
if [ -d "$II_AGENT_DIR" ]; then
    echo "→ II-Agent source already exists at $II_AGENT_DIR"
    echo "→ Pulling latest..."
    cd "$II_AGENT_DIR" && git pull origin main
else
    echo "→ Cloning II-Agent..."
    git clone --depth 1 https://github.com/Intelligent-Internet/ii-agent.git "$II_AGENT_DIR"
fi

# Step 2: Copy env if not exists
if [ ! -f "${SCRIPT_DIR}/docker/ii-agent/.env" ]; then
    cp "${SCRIPT_DIR}/docker/ii-agent/.env.example" "${SCRIPT_DIR}/docker/ii-agent/.env"
    echo "→ Created docker/ii-agent/.env — please edit with your API keys"
fi

# Step 3: Remind about .env
echo ""
echo "=== Setup Complete ==="
echo ""
echo "Next steps:"
echo "  1. Edit .env and add: OPENAI_API_KEY, ANTHROPIC_API_KEY"
echo "  2. docker compose build ii-agent"
echo "  3. docker compose up -d ii-agent ii-agent-postgres"
echo "  4. Test: curl http://localhost:8900/health"
echo ""
