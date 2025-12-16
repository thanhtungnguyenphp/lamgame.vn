#!/bin/bash

echo "🧪 Testing Structured Data Implementation"
echo "=========================================="
echo ""

# Test Job Schema
echo "📋 Testing Job Schema..."
docker exec lamgame-php php artisan tinker --execute="
\$job = DB::table('products as p')
    ->join('product_flat as pf', function(\$join) {
        \$join->on('p.id', '=', 'pf.product_id')->where('pf.locale', '=', 'vi');
    })
    ->where('p.type', 'job')
    ->select('p.*', 'pf.name', 'pf.url_key', 'pf.price')
    ->first();
if (\$job) {
    \$schemas = App\Helpers\StructuredDataHelper::generateAll('job', \$job);
    echo '✅ Generated ' . count(\$schemas) . ' schemas for job';
    echo PHP_EOL . '   Types: ';
    foreach (\$schemas as \$schema) {
        \$data = json_decode(\$schema, true);
        echo \$data['@type'] . ' ';
    }
    echo PHP_EOL . '   URL: /viec-lam/' . \$job->url_key;
} else {
    echo '❌ No job found';
}
"

echo ""
echo ""

# Test Blog Schema
echo "📝 Testing Blog Schema..."
docker exec lamgame-php php artisan tinker --execute="
\$blog = App\Models\Blog::published()->first();
if (\$blog) {
    \$schemas = App\Helpers\StructuredDataHelper::generateAll('blog', \$blog);
    echo '✅ Generated ' . count(\$schemas) . ' schemas for blog';
    echo PHP_EOL . '   Types: ';
    foreach (\$schemas as \$schema) {
        \$data = json_decode(\$schema, true);
        echo \$data['@type'] . ' ';
    }
    echo PHP_EOL . '   URL: /blog/' . \$blog->slug;
} else {
    echo '❌ No blog found';
}
"

echo ""
echo ""
echo "=========================================="
echo "✅ Structured Data Test Complete!"
echo ""
echo "📖 Next Steps:"
echo "   1. Visit a job page and view source"
echo "   2. Look for <script type=\"application/ld+json\">"
echo "   3. Test with: https://search.google.com/test/rich-results"
echo "   4. Validate: https://validator.schema.org/"
