<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductVariation;
use App\Models\ProductImage;
use App\Models\StockLedger;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->warn('ProductSeeder skipped: cannot seed demo products in production.');
            return;
        }

        $brand = Brand::firstOrCreate(['name' => 'Buzz'], ['active_status' => 1, 'logo' => 'brands/placeholder.png']);
        $colors = ProductColor::all();
        $sizes = ProductSize::all();

        // Ensure categories & subcategories are loaded
        $categories = Category::with('subCategories')->get()->keyBy('name');

        $productsData = [
            // ================= MEN'S COLLECTION (1 - 10) =================
            [
                'id' => 1,
                'name' => 'Signature Semi-Fitted Jacquard Panjabi',
                'category' => 'Men',
                'subcategory' => 'Panjabi & Festive',
                'price' => 2850,
                'is_new_arrival' => 1,
                'is_featured' => 1,
                'is_best_seller' => 1,
                'is_on_sale' => 0,
                'is_trending' => 1,
                'short_description' => 'Crafted from fine viscose-cotton jacquard with a subtle geometric weave and metallic snap buttons, designed for festive gatherings and Eid celebrations.',
                'description' => '<p>Elevate your traditional wardrobe with this Signature Semi-Fitted Jacquard Panjabi from Buzz Bangladesh. Tailored from a premium viscose-cotton blend, it offers a luxurious sheen, featherlight drape, and maximum breathability for all-day festive comfort.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 70% Viscose, 30% Combed Cotton Jacquard Weave</li><li><strong>Fit:</strong> Contemporary Semi-Fitted silhouette</li><li><strong>Collar:</strong> Structured Mandarin band collar with tonal contrast stitch</li><li><strong>Closure:</strong> Antique metallic snap buttons with engraved accents</li><li><strong>Pockets:</strong> Dual functional side welt pockets</li></ul><h4>Care Instructions</h4><p>Dry clean recommended for first wash. Gentle cold hand wash inside-out with mild liquid detergent. Warm iron on reverse side. Do not wring or tumble dry.</p>',
                'seo_tags' => 'panjabi, festive, eid collection, mens fashion, ethnic wear, buzz bangladesh',
            ],
            [
                'id' => 2,
                'name' => 'Minimalist Royal Kabli Suit with Pajama',
                'category' => 'Men',
                'subcategory' => 'Panjabi & Festive',
                'price' => 3650,
                'is_new_arrival' => 1,
                'is_featured' => 1,
                'is_best_seller' => 0,
                'is_on_sale' => 0,
                'is_trending' => 1,
                'short_description' => 'Two-piece premium cotton blend Kabli set featuring a modern flap-pocket jacket cut and matching drawstring trousers.',
                'description' => '<p>Step into understated elegance with the Minimalist Royal Kabli Suit. Crafted for the modern Bangladeshi man, this set pairs a structured military-inspired kurta with tailored trousers for weddings, evening daawats, and festive functions.</p><h4>Key Features</h4><ul><li><strong>Set Includes:</strong> Kabli Kurta and matching straight-cut pajama</li><li><strong>Fabric:</strong> 100% High-Density Combed Cotton Twill</li><li><strong>Details:</strong> Chest flap pockets with discreet metal rivets, epaulet shoulders</li><li><strong>Fit:</strong> Tailored Modern Fit</li></ul><h4>Care Instructions</h4><p>Machine wash cold on gentle cycle. Hang dry in shade. Warm steam iron for a crisp finish.</p>',
                'seo_tags' => 'kabli suit, men kabli, festive suit, panjabi pajama, bangladesh fashion',
            ],
            [
                'id' => 3,
                'name' => 'Classic Heavyweight Pique Knit Polo Shirt',
                'category' => 'Men',
                'subcategory' => 'T-Shirts & Polos',
                'price' => 1150,
                'is_new_arrival' => 0,
                'is_featured' => 1,
                'is_best_seller' => 1,
                'is_on_sale' => 0,
                'is_trending' => 0,
                'short_description' => 'Cut from 230 GSM combed cotton honeycomb pique with a shape-retaining ribbed collar and clean double-stitched hem.',
                'description' => '<p>A quintessential smart-casual staple. The Classic Heavyweight Pique Polo provides superior structure without sacrificing ventilation. Ideal for casual Fridays at the office or laid-back weekends.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 100% Combed Cotton Honeycomb Pique (230 GSM)</li><li><strong>Collar:</strong> Shape-retentive flat-knit ribbed collar and cuffs</li><li><strong>Placket:</strong> Two-button placket with pearlized engraved buttons</li><li><strong>Side Slits:</strong> Reinforced herringbone tape at hem slits for ease of movement</li></ul><h4>Care Instructions</h4><p>Machine wash warm with like colors. Line dry in shade. Do not bleach.</p>',
                'seo_tags' => 'polo shirt, pique polo, mens polo, casual wear, buzz bangladesh',
            ],
            [
                'id' => 4,
                'name' => 'Bio-Washed Supima Crewneck T-Shirt',
                'category' => 'Men',
                'subcategory' => 'T-Shirts & Polos',
                'price' => 650,
                'is_new_arrival' => 1,
                'is_featured' => 0,
                'is_best_seller' => 1,
                'is_on_sale' => 0,
                'is_trending' => 1,
                'short_description' => 'Ultra-soft 180 GSM long-staple combed cotton treated with enzyme bio-wash for silk-like hand feel and zero pill resistance.',
                'description' => '<p>The ultimate daily essential. Crafted from premium long-staple cotton, this crewneck t-shirt delivers unbelievable softness and durability. Retains its deep color and shape through countless washes.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 100% Long-Staple Combed Cotton (180 GSM)</li><li><strong>Wash:</strong> Silicone enzyme bio-wash for unmatched softness</li><li><strong>Neckline:</strong> 1x1 ribbed crew collar with lycra for elasticity</li><li><strong>Fit:</strong> Standard Athletic Fit</li></ul><h4>Care Instructions</h4><p>Machine wash cold. Do not tumble dry high. Iron inside-out if required.</p>',
                'seo_tags' => 't-shirt, crewneck, basic tee, combed cotton, men t-shirt',
            ],
            [
                'id' => 5,
                'name' => 'Tailored Slim Fit Oxford Button-Down Shirt',
                'category' => 'Men',
                'subcategory' => 'Casual & Formal Shirts',
                'price' => 1650,
                'is_new_arrival' => 0,
                'is_featured' => 1,
                'is_best_seller' => 0,
                'is_on_sale' => 1,
                'is_trending' => 0,
                'short_description' => 'Woven from two-ply 100% combed cotton Oxford weave with roll button-down collar and single chest pocket.',
                'description' => '<p>The quintessential versatile shirt. Transition effortlessly from morning boardrooms to rooftop evenings with our Tailored Slim Fit Oxford Shirt. Built to last with durable two-ply yarns and neat single-needle tailoring.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 100% Cotton Oxford Cloth (Basket Weave)</li><li><strong>Collar:</strong> Classic button-down collar with natural roll</li><li><strong>Cuffs:</strong> Rounded barrel cuffs with adjustable button stance</li><li><strong>Hem:</strong> Curved shirttail hem that stays neatly tucked</li></ul><h4>Care Instructions</h4><p>Warm machine wash. Medium iron with starch for formal occasions.</p>',
                'seo_tags' => 'oxford shirt, button down, formal shirt, casual shirt, mens shirts bd',
            ],
            [
                'id' => 6,
                'name' => 'Breezy Cuban Collar Linen Resort Shirt',
                'category' => 'Men',
                'subcategory' => 'Casual & Formal Shirts',
                'price' => 1450,
                'is_new_arrival' => 1,
                'is_featured' => 0,
                'is_best_seller' => 0,
                'is_on_sale' => 1,
                'is_trending' => 1,
                'short_description' => 'Airy linen-cotton blend shirt styled with an open camp collar and straight boxy hem for effortless tropical vibes.',
                'description' => '<p>Embrace hot summer days in relaxed comfort. Tailored from pre-washed linen and breathable cotton, this camp-collar shirt exudes effortless tropical sophistication.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 55% French Linen, 45% Combed Cotton</li><li><strong>Collar:</strong> Retro notched Cuban camp collar</li><li><strong>Cut:</strong> Relaxed Boxy Fit with straight vented hem</li><li><strong>Buttons:</strong> Natural sustainable coconut-shell buttons</li></ul><h4>Care Instructions</h4><p>Gentle cycle cold wash. Dry flat. Embrace the natural linen crinkle or warm steam iron.</p>',
                'seo_tags' => 'linen shirt, cuban collar, camp shirt, resort wear, summer fashion',
            ],
            [
                'id' => 7,
                'name' => 'Vintage Indigo Slim-Tapered Stretch Denim Jeans',
                'category' => 'Men',
                'subcategory' => 'Jeans & Chinos',
                'price' => 2250,
                'is_new_arrival' => 0,
                'is_featured' => 1,
                'is_best_seller' => 1,
                'is_on_sale' => 0,
                'is_trending' => 1,
                'short_description' => '12.5 oz comfort-stretch denim featuring authentic whiskering, hand-sanded fades, and reinforced pocket rivets.',
                'description' => '<p>Engineered for comfort and durability. Our Vintage Indigo Stretch Jeans combine authentic heavy denim texture with 2% elastane for complete freedom of movement.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 98% Cotton, 2% Spandex (12.5 oz Denim)</li><li><strong>Fit:</strong> Slim through thigh, slightly tapered leg opening</li><li><strong>Hardware:</strong> Heavy-duty YKK brass zipper with Buzz branded shank button</li><li><strong>Wash:</strong> Enzyme stone-wash with subtle hand-sanding</li></ul><h4>Care Instructions</h4><p>Wash inside-out in cold water. Avoid frequent washing to preserve indigo dye patina.</p>',
                'seo_tags' => 'denim jeans, stretch jeans, slim fit jeans, mens pants, buzz denim',
            ],
            [
                'id' => 8,
                'name' => 'Smart Everyday Stretch Cotton Chino Trousers',
                'category' => 'Men',
                'subcategory' => 'Jeans & Chinos',
                'price' => 1850,
                'is_new_arrival' => 1,
                'is_featured' => 0,
                'is_best_seller' => 0,
                'is_on_sale' => 0,
                'is_trending' => 0,
                'short_description' => 'Tailored from combed cotton twill with an active flex waistband, double welt rear pockets, and a clean flat front.',
                'description' => '<p>The versatile foundation of any modern wardrobe. These stretch chinos combine the sharpness of formal trousers with the comfort of weekend loungewear.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 97% Combed Cotton, 3% Elastane Twill</li><li><strong>Waistband:</strong> Internal flex-stretch band that gives up to 1 inch</li><li><strong>Details:</strong> Clean flat front, slash side pockets, button-through back pockets</li><li><strong>Cut:</strong> Clean Modern Tapered Fit</li></ul><h4>Care Instructions</h4><p>Machine wash cold with similar colors. Line dry in shade.</p>',
                'seo_tags' => 'chinos, cotton trousers, formal pants, casual trousers, mens chinos',
            ],
            [
                'id' => 9,
                'name' => 'Aero-Dry Performance Athletic Joggers',
                'category' => 'Men',
                'subcategory' => 'Activewear',
                'price' => 1250,
                'is_new_arrival' => 0,
                'is_featured' => 0,
                'is_best_seller' => 1,
                'is_on_sale' => 1,
                'is_trending' => 1,
                'short_description' => 'Lightweight moisture-wicking 4-way stretch fabric with zip-secured pockets and tapered ribbed ankle cuffs.',
                'description' => '<p>Engineered for high-intensity training, jogging, or weekend errands. Aero-Dry fabric wicks sweat away from skin instantly, keeping you cool and focused.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 88% Poly-Interlock, 12% Spandex (Aero-Dry Technology)</li><li><strong>Pockets:</strong> Dual deep zippered side pockets for phone security</li><li><strong>Waist:</strong> Elastic drawstring waistband with rubber-tipped cords</li><li><strong>Ankle:</strong> Ergonomic ribbed cuffs with sleek contour stitching</li></ul><h4>Care Instructions</h4><p>Machine wash cold. Do not use fabric softeners. Air dry quickly.</p>',
                'seo_tags' => 'joggers, activewear, sweatpants, gym pants, athletic wear',
            ],
            [
                'id' => 10,
                'name' => 'Seamless Breathable Gym Compression Tee',
                'category' => 'Men',
                'subcategory' => 'Activewear',
                'price' => 750,
                'is_new_arrival' => 1,
                'is_featured' => 1,
                'is_best_seller' => 0,
                'is_on_sale' => 0,
                'is_trending' => 0,
                'short_description' => 'Chafe-free raglan construction with targeted mesh ventilation panels across back and underarms for maximum airflow.',
                'description' => '<p>Designed to move with your body during demanding workouts. Featuring zoned mesh knit for thermal regulation and flatlock stitching that eliminates friction against skin.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 90% Polyamide, 10% Elastane</li><li><strong>Design:</strong> Raglan sleeves for unrestricted arm range</li><li><strong>Breathability:</strong> Micro-perforated thermal zones</li><li><strong>Antimicrobial:</strong> Silver-ion treated to prevent odor buildup</li></ul><h4>Care Instructions</h4><p>Machine wash cold. Hang dry. Do not iron.</p>',
                'seo_tags' => 'gym tee, workout shirt, compression tee, athletic top, sportswear',
            ],

            // ================= WOMEN'S COLLECTION (11 - 20) =================
            [
                'id' => 11,
                'name' => 'Handwoven Dhakai Jamdani Fine Cotton Saree',
                'category' => 'Women',
                'subcategory' => 'Ethnic Wear & Sarees',
                'price' => 5500,
                'is_new_arrival' => 1,
                'is_featured' => 1,
                'is_best_seller' => 1,
                'is_on_sale' => 0,
                'is_trending' => 1,
                'short_description' => 'Authentic heritage weave featuring delicate geometric floral motifs hand-loomed in fine combed cotton thread with matching blouse piece.',
                'description' => '<p>Celebrate Bangladesh\'s rich textile heritage with this exquisite Handwoven Dhakai Jamdani Saree. Meticulously loomed by master artisans, each motif is woven by hand onto sheer, breathable cotton cloth.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 100% Fine Combed Muslin Cotton</li><li><strong>Weave:</strong> Traditional Supplementary Weft Jamdani Technique</li><li><strong>Includes:</strong> 5.5 Meter Saree + 0.8 Meter Running Blouse Piece</li><li><strong>Pallu:</strong> Elaborate floral paisley anchor design</li></ul><h4>Care Instructions</h4><p>Dry clean only. Store wrapped in acid-free tissue paper or pure cotton muslin.</p>',
                'seo_tags' => 'jamdani saree, dhakai saree, cotton saree, traditional saree, bangladeshi saree',
            ],
            [
                'id' => 12,
                'name' => 'Embroidered Georgette Anarkali Suit Set',
                'category' => 'Women',
                'subcategory' => 'Ethnic Wear & Sarees',
                'price' => 4850,
                'is_new_arrival' => 0,
                'is_featured' => 1,
                'is_best_seller' => 0,
                'is_on_sale' => 1,
                'is_trending' => 1,
                'short_description' => 'Three-piece festive set detailed with intricate zari embroidery and micro-sequins, paired with churidar and sheer chiffon dupatta.',
                'description' => '<p>Command admiration at weddings and evening galas. This regal Anarkali suit features a voluminous flared silhouette adorned with detailed metallic zari work and tonal sequins.</p><h4>Key Features</h4><ul><li><strong>Set:</strong> 3 Pieces (Embroidered Anarkali Kurta, Churidar Pants, Dupatta)</li><li><strong>Fabric:</strong> Premium Faux Georgette with Shantoon inner lining</li><li><strong>Work:</strong> Zari thread embroidery with micro-sequin embellishments</li><li><strong>Dupatta:</strong> Sheer lightweight chiffon with embroidered scalloped borders</li></ul><h4>Care Instructions</h4><p>Professional dry clean only. Do not bleach or spray perfume directly on embroidery.</p>',
                'seo_tags' => 'anarkali suit, salwar kameez, party dress, wedding guest, womens ethnic',
            ],
            [
                'id' => 13,
                'name' => 'Tiered Floral Georgette Maxi Dress',
                'category' => 'Women',
                'subcategory' => 'Dresses & Gowns',
                'price' => 2650,
                'is_new_arrival' => 1,
                'is_featured' => 1,
                'is_best_seller' => 1,
                'is_on_sale' => 0,
                'is_trending' => 1,
                'short_description' => 'Romantic tiered maxi dress with smocked elastic bodice, flutter sleeves, and sweeping floor-length skirt in botanical print.',
                'description' => '<p>Float effortlessly through sunny brunches, garden parties, or vacation evenings. Designed with delicate ruffle tiers and a flexible smocked bodice that contours gracefully.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 100% Poly-Georgette with soft opaque cotton lining</li><li><strong>Bodice:</strong> Shirred smocked elasticity for customizable fit</li><li><strong>Sleeves:</strong> Sheer flutter sleeves with ruffled trim</li><li><strong>Skirt:</strong> Triple tiered ruffle maxi skirt</li></ul><h4>Care Instructions</h4><p>Cold hand wash or gentle machine wash inside a laundry bag. Line dry.</p>',
                'seo_tags' => 'maxi dress, floral dress, summer gown, womens dress, buzz fashion',
            ],
            [
                'id' => 14,
                'name' => 'Pure Linen A-Line Summer Wrap Dress',
                'category' => 'Women',
                'subcategory' => 'Dresses & Gowns',
                'price' => 2250,
                'is_new_arrival' => 0,
                'is_featured' => 0,
                'is_best_seller' => 0,
                'is_on_sale' => 1,
                'is_trending' => 0,
                'short_description' => 'Tailored from 100% French linen with a self-tie sash waistband, crossover V-neckline, and modest midi length.',
                'description' => '<p>Understated luxury meets tropical comfort. This pure linen wrap dress is a timeless silhouette that flatters every figure with its adjustable waist sash.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 100% Pure Flax Linen (Pre-shrunk)</li><li><strong>Neckline:</strong> Flattering crossover V-neck</li><li><strong>Closure:</strong> Internal button anchor with exterior wraparound sash</li><li><strong>Pockets:</strong> Hidden in-seam side pockets</li></ul><h4>Care Instructions</h4><p>Machine wash cold on delicate. Air dry. Light steam iron while slightly damp.</p>',
                'seo_tags' => 'linen dress, wrap dress, midi dress, casual dress, summer dress',
            ],
            [
                'id' => 15,
                'name' => 'Hand-Embroidered Kantha Cotton Kurti',
                'category' => 'Women',
                'subcategory' => 'Tops & Kurtis',
                'price' => 1450,
                'is_new_arrival' => 0,
                'is_featured' => 1,
                'is_best_seller' => 1,
                'is_on_sale' => 0,
                'is_trending' => 0,
                'short_description' => 'Pure cotton daily kurti accented with artisanal Kantha running stitches along neckline, cuffs, and side slit hems.',
                'description' => '<p>A fusion of folk art and contemporary workwear. Made from breathable handloom cotton, this kurti showcases traditional rural Kantha stitch detailing.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 100% Breathable Combed Cotton</li><li><strong>Work:</strong> Handcrafted Kantha stitch embroidery</li><li><strong>Length:</strong> Knee-length straight silhouette with side vents</li><li><strong>Sleeves:</strong> Three-quarter sleeves with contrast piped cuffs</li></ul><h4>Care Instructions</h4><p>Cold hand wash with mild liquid detergent. Dry in shade.</p>',
                'seo_tags' => 'kurti, cotton kurti, kantha stitch, ethnic top, daily kurti',
            ],
            [
                'id' => 16,
                'name' => 'Lustrous Silk-Blend Peplum Blouse',
                'category' => 'Women',
                'subcategory' => 'Tops & Kurtis',
                'price' => 1650,
                'is_new_arrival' => 1,
                'is_featured' => 0,
                'is_best_seller' => 0,
                'is_on_sale' => 0,
                'is_trending' => 1,
                'short_description' => 'Structured silk-viscose blend blouse featuring a cinched peplum waist, jewel neckline, and concealed back zipper.',
                'description' => '<p>Sophisticated and modern. Perfect for formal dinners, corporate meetings, or dinner dates when paired with sleek tailored trousers.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 60% Viscose, 40% Mulberry Silk</li><li><strong>Cut:</strong> Structured peplum flare accentuating the waist</li><li><strong>Neckline:</strong> Minimalist round jewel neckline</li><li><strong>Closure:</strong> Concealed invisible YKK back zipper</li></ul><h4>Care Instructions</h4><p>Dry clean or gentle cold hand wash. Cool iron on reverse.</p>',
                'seo_tags' => 'peplum blouse, silk top, formal top, womens blouse, chic tops',
            ],
            [
                'id' => 17,
                'name' => 'Relaxed Fit Modal Everyday Tunic',
                'category' => 'Women',
                'subcategory' => 'Tops & Kurtis',
                'price' => 1150,
                'is_new_arrival' => 0,
                'is_featured' => 0,
                'is_best_seller' => 1,
                'is_on_sale' => 1,
                'is_trending' => 0,
                'short_description' => 'Buttery soft modal knit tunic top with curved high-low hemline and relaxed dropped shoulders for effortless layering.',
                'description' => '<p>Softness you have to feel to believe. Modal fabric provides fluid drape that resists wrinkles and feels silky smooth against sensitive skin.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 95% Micro-Modal, 5% Spandex</li><li><strong>Drape:</strong> High-low curved hemline covering hips</li><li><strong>Neckline:</strong> Relaxed scoop neck</li><li><strong>Fit:</strong> Easy relaxed drape</li></ul><h4>Care Instructions</h4><p>Machine wash cold on gentle. Lay flat to dry.</p>',
                'seo_tags' => 'tunic, modal top, casual tunic, daily wear, comfortable tops',
            ],
            [
                'id' => 18,
                'name' => 'High-Waisted Wide-Leg Pleated Trousers',
                'category' => 'Women',
                'subcategory' => 'Pants & Skirts',
                'price' => 1750,
                'is_new_arrival' => 1,
                'is_featured' => 1,
                'is_best_seller' => 0,
                'is_on_sale' => 0,
                'is_trending' => 1,
                'short_description' => 'Double-pleated tailored wide-leg trousers cut from premium crepe twill with deep slash pockets and clean waistband.',
                'description' => '<p>Chic, commanding, and infinitely stylish. These high-waisted trousers elongate the silhouette and pair seamlessly with crop tops or button-downs.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> Heavy Crepe Poly-Viscose Twill</li><li><strong>Rise:</strong> High-rise fitted waist with belt loops</li><li><strong>Leg Cut:</strong> Wide leg fluid drape with sharp front pleats</li><li><strong>Pockets:</strong> Deep functional side slant pockets</li></ul><h4>Care Instructions</h4><p>Machine wash cold. Hang dry. Medium steam iron.</p>',
                'seo_tags' => 'wide leg pants, trousers, pleated pants, formal pants women, high waist',
            ],
            [
                'id' => 19,
                'name' => 'Accordion Pleated Satin Midi Skirt',
                'category' => 'Women',
                'subcategory' => 'Pants & Skirts',
                'price' => 1450,
                'is_new_arrival' => 0,
                'is_featured' => 0,
                'is_best_seller' => 1,
                'is_on_sale' => 1,
                'is_trending' => 0,
                'short_description' => 'Fluid satin midi skirt crafted with knife-edge accordion pleats and an elasticated metallic waistband.',
                'description' => '<p>Add motion and glamour to your ensemble. This pleated midi skirt catches light beautifully and moves with dramatic fluidity with every step.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 100% Silky Polyester Satin</li><li><strong>Waist:</strong> Stretchy comfortable waistband with metallic sheen</li><li><strong>Pleats:</strong> Permanent thermal knife pleats that never lose shape</li><li><strong>Length:</strong> Elegant midi length hitting mid-calf</li></ul><h4>Care Instructions</h4><p>Hand wash cold. Do not iron pleats directly. Hang to dry.</p>',
                'seo_tags' => 'pleated skirt, satin skirt, midi skirt, party skirt, womens bottom',
            ],
            [
                'id' => 20,
                'name' => 'Stretch Cotton Ankle Cigarette Pants',
                'category' => 'Women',
                'subcategory' => 'Pants & Skirts',
                'price' => 1350,
                'is_new_arrival' => 0,
                'is_featured' => 0,
                'is_best_seller' => 0,
                'is_on_sale' => 0,
                'is_trending' => 0,
                'short_description' => 'Tailored cigarette pants in stretch cotton sateen with subtle ankle side vents and flat smoothing waistband.',
                'description' => '<p>The ultimate daily bottom for work or weekend. Tailored to fit snugly through the hips and taper to an ankle-grazing hem with discreet side slits.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 96% Cotton Sateen, 4% Elastane</li><li><strong>Closure:</strong> Side zip closure for flat front silhouette</li><li><strong>Details:</strong> Side hem ankle slits</li><li><strong>Fit:</strong> Slim Cigarette Fit</li></ul><h4>Care Instructions</h4><p>Machine wash cold. Tumble dry low.</p>',
                'seo_tags' => 'cigarette pants, slim pants, ankle pants, formal pants, womens trousers',
            ],

            // ================= KIDS COLLECTION (21 - 30) =================
            [
                'id' => 21,
                'name' => 'Boys Festive Embroidered Cotton Kurta',
                'category' => 'Kids',
                'subcategory' => 'Boys Collection',
                'price' => 1250,
                'is_new_arrival' => 1,
                'is_featured' => 1,
                'is_best_seller' => 1,
                'is_on_sale' => 0,
                'is_trending' => 1,
                'short_description' => 'Soft, hypoallergenic pure cotton kurta with delicate neckline embroidery and mother-of-pearl buttons for little gentlemen.',
                'description' => '<p>Make Eid and special occasions memorable for your boy. Tailored from featherlight 100% pure cotton that keeps boys cool and comfortable all day long.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 100% Hypoallergenic Combed Cotton</li><li><strong>Work:</strong> Fine thread embroidery around Mandarin collar and placket</li><li><strong>Buttons:</strong> Smooth, non-scratch mother-of-pearl buttons</li><li><strong>Fit:</strong> Easy comfort fit allowing free play</li></ul><h4>Care Instructions</h4><p>Gentle machine wash with mild baby-safe detergent. Warm iron.</p>',
                'seo_tags' => 'boys kurta, kids panjabi, eid kurta, boys ethnic, kids clothes bd',
            ],
            [
                'id' => 22,
                'name' => 'Boys Adventure Explorer Graphic Tee',
                'category' => 'Kids',
                'subcategory' => 'Boys Collection',
                'price' => 450,
                'is_new_arrival' => 0,
                'is_featured' => 0,
                'is_best_seller' => 1,
                'is_on_sale' => 0,
                'is_trending' => 0,
                'short_description' => 'Durable 100% combed cotton jersey tee featuring non-toxic, skin-friendly water-based typography and animal prints.',
                'description' => '<p>Built for active play and everyday adventures. Made from heavy-duty yet soft cotton jersey that withstands the playground and endless washes.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 100% Combed Single Jersey Cotton (180 GSM)</li><li><strong>Print:</strong> Eco-friendly, odorless water-based screen print</li><li><strong>Neck:</strong> Ribbed stretch neck that slides on easily</li><li><strong>Label:</strong> Tagless printed neckline for zero itching</li></ul><h4>Care Instructions</h4><p>Machine wash warm. Tumble dry normal.</p>',
                'seo_tags' => 'boys t-shirt, graphic tee, kids t-shirt, cartoon tee, boys clothes',
            ],
            [
                'id' => 23,
                'name' => 'Boys Soft-Washed Stretch Denim Cargo Shorts',
                'category' => 'Kids',
                'subcategory' => 'Boys Collection',
                'price' => 750,
                'is_new_arrival' => 0,
                'is_featured' => 0,
                'is_best_seller' => 0,
                'is_on_sale' => 1,
                'is_trending' => 1,
                'short_description' => 'Enzyme-washed stretch denim shorts equipped with an adjustable inner button waistband and roomy flap cargo pockets.',
                'description' => '<p>Rugged, trendy, and ready for adventure. Soft stretch denim ensures unrestricted running, climbing, and playtime.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 98% Cotton, 2% Spandex Stretch Denim</li><li><strong>Waist:</strong> Adjustable internal elastic buttonhole waist</li><li><strong>Pockets:</strong> 2 front slant pockets, 2 side cargo flap pockets</li><li><strong>Closure:</strong> Easy snap button for small hands</li></ul><h4>Care Instructions</h4><p>Machine wash cold with like colors.</p>',
                'seo_tags' => 'boys shorts, cargo shorts, denim shorts kids, boys summer, kids clothing',
            ],
            [
                'id' => 24,
                'name' => 'Boys Brushed Fleece Zip-Up Hoodie',
                'category' => 'Kids',
                'subcategory' => 'Boys Collection',
                'price' => 1350,
                'is_new_arrival' => 1,
                'is_featured' => 1,
                'is_best_seller' => 0,
                'is_on_sale' => 0,
                'is_trending' => 0,
                'short_description' => 'Cozy brushed fleece jacket featuring a smooth nylon zipper, double-layered hood, and split kangaroo hand pockets.',
                'description' => '<p>Keeps kids warm during winter mornings and chilly school commutes. The plush interior fleece provides exceptional warmth without unnecessary bulk.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 80% Cotton, 20% Polyester Brushed Fleece (280 GSM)</li><li><strong>Zipper:</strong> Smooth glide YKK nylon zipper with protective chin guard</li><li><strong>Hood:</strong> Double-layered jersey lined hood</li><li><strong>Cuffs:</strong> Ribbed cuffs and hem to lock out drafts</li></ul><h4>Care Instructions</h4><p>Machine wash cold. Tumble dry low.</p>',
                'seo_tags' => 'kids hoodie, boys jacket, winter hoodie, fleece jacket, boys winter',
            ],
            [
                'id' => 25,
                'name' => 'Girls Embroidered Organza Party Frock',
                'category' => 'Kids',
                'subcategory' => 'Girls Collection',
                'price' => 1950,
                'is_new_arrival' => 1,
                'is_featured' => 1,
                'is_best_seller' => 1,
                'is_on_sale' => 0,
                'is_trending' => 1,
                'short_description' => 'Enchanting party dress with multi-layered soft spun organza, delicate floral thread embroidery, and pure cotton inner lining.',
                'description' => '<p>Fit for a little princess. This fairy-tale party dress pairs gossamer organza ruffles with a delicate floral embroidered bodice and smooth cotton lining.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> Premium Sheer Organza with 100% Breathable Cotton Voile Lining</li><li><strong>Details:</strong> Hand-finished floral embroidery, satin tie-back bow</li><li><strong>Closure:</strong> Concealed back zipper with safety tab</li><li><strong>Skirt:</strong> Multi-tiered flared crinoline volume</li></ul><h4>Care Instructions</h4><p>Gentle cold hand wash. Hang to dry. Do not bleach.</p>',
                'seo_tags' => 'girls party dress, frock, girls gown, princess dress, kids festive',
            ],
            [
                'id' => 26,
                'name' => 'Girls Pastel Floral Lawn Summer Sundress',
                'category' => 'Kids',
                'subcategory' => 'Girls Collection',
                'price' => 850,
                'is_new_arrival' => 0,
                'is_featured' => 0,
                'is_best_seller' => 1,
                'is_on_sale' => 0,
                'is_trending' => 0,
                'short_description' => 'Airy 100% lawn cotton sundress with sweet flutter sleeves, empire waistline, and vibrant pastel daisy print.',
                'description' => '<p>Cheerful, light, and wonderfully breezy. Perfect for hot summer afternoons, family outings, and holiday play.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 100% Lightweight Lawn Cotton</li><li><strong>Sleeves:</strong> Ruffled flutter shoulder sleeves</li><li><strong>Back:</strong> Wooden button keyhole closure at back neck</li><li><strong>Fit:</strong> Flared A-line silhouette</li></ul><h4>Care Instructions</h4><p>Machine wash cold. Tumble dry gentle or line dry.</p>',
                'seo_tags' => 'girls sundress, cotton frock, floral dress kids, summer dress girls',
            ],
            [
                'id' => 27,
                'name' => 'Girls Vintage Stretch Denim Overalls',
                'category' => 'Kids',
                'subcategory' => 'Girls Collection',
                'price' => 1150,
                'is_new_arrival' => 0,
                'is_featured' => 0,
                'is_best_seller' => 0,
                'is_on_sale' => 1,
                'is_trending' => 1,
                'short_description' => 'Classic bib overalls in soft enzyme-washed denim with adjustable metal clasp shoulder straps and embroidered bib pocket.',
                'description' => '<p>Retro charm built for playground fun. Features soft stretch denim, adjustable shoulder buckles, and cute embroidered floral pocket accent.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 99% Cotton, 1% Spandex Soft Stretch Denim</li><li><strong>Straps:</strong> Adjustable metal hook and slider buckles</li><li><strong>Hardware:</strong> Side waist shank buttons for easy dressing</li><li><strong>Pockets:</strong> Chest bib pocket with cute daisy motif</li></ul><h4>Care Instructions</h4><p>Machine wash inside-out with cold water.</p>',
                'seo_tags' => 'girls overalls, denim dungaree, kids dungarees, denim overall',
            ],
            [
                'id' => 28,
                'name' => 'Infant Organic Cotton Bodysuits (Pack of 3)',
                'category' => 'Kids',
                'subcategory' => 'Infants & Toddlers',
                'price' => 1200,
                'is_new_arrival' => 1,
                'is_featured' => 1,
                'is_best_seller' => 1,
                'is_on_sale' => 0,
                'is_trending' => 0,
                'short_description' => 'Pack of 3 certified organic cotton onesies with nickel-free crotch snaps and expandable lap shoulder necklines for newborns.',
                'description' => '<p>Only the purest fabric for your newborn baby. Made from 100% certified organic cotton, free from toxic dyes, pesticides, or irritating chemicals.</p><h4>Key Features</h4><ul><li><strong>Pack:</strong> Includes 3 complementary pastel colorways</li><li><strong>Fabric:</strong> 100% GOTS Certified Organic Ribbed Cotton</li><li><strong>Snaps:</strong> Nickel-free bottom snaps for swift diaper changes</li><li><strong>Shoulders:</strong> Overlapping lap collar that slides down over shoulders easily</li></ul><h4>Care Instructions</h4><p>Machine wash warm with baby-safe hypoallergenic detergent.</p>',
                'seo_tags' => 'baby bodysuits, onesie, infant clothing, newborn clothes, organic baby',
            ],
            [
                'id' => 29,
                'name' => 'Toddler Soft Knit Romper with Wood Buttons',
                'category' => 'Kids',
                'subcategory' => 'Infants & Toddlers',
                'price' => 850,
                'is_new_arrival' => 0,
                'is_featured' => 0,
                'is_best_seller' => 0,
                'is_on_sale' => 1,
                'is_trending' => 0,
                'short_description' => 'One-piece waffle knit cotton romper with natural wood button front placket and snap inseam for effortless dressing.',
                'description' => '<p>Cozy minimalist style for your little explorer. Textured breathable waffle knit ensures baby stays at the perfect temperature indoors or out.</p><h4>Key Features</h4><ul><li><strong>Fabric:</strong> 100% Combed Cotton Waffle Knit</li><li><strong>Front:</strong> Natural coconut/wood buttons on center placket</li><li><strong>Diaper Access:</strong> Hidden reinforced snap tape along inner leg inseam</li><li><strong>Fit:</strong> Roomy diaper-friendly gusset</li></ul><h4>Care Instructions</h4><p>Gentle cycle cold. Lay flat to dry.</p>',
                'seo_tags' => 'baby romper, toddler romper, waffle knit, infant jumpsuit, baby clothes',
            ],
            [
                'id' => 30,
                'name' => 'Toddler Reversible Sun Hat & Bandana Bib Set',
                'category' => 'Kids',
                'subcategory' => 'Infants & Toddlers',
                'price' => 550,
                'is_new_arrival' => 1,
                'is_featured' => 0,
                'is_best_seller' => 0,
                'is_on_sale' => 0,
                'is_trending' => 1,
                'short_description' => 'Two-piece accessory set featuring a wide-brim UPF 50+ sun bucket hat paired with an absorbent double-layer drool bib.',
                'description' => '<p>Keep baby shielded from sun and dry during teething. The bucket hat offers broad UPF 50+ shade, while the bandana bib absorbs spills in style.</p><h4>Key Features</h4><ul><li><strong>Set:</strong> 1 Reversible Sun Hat + 1 Triangular Bandana Bib</li><li><strong>Fabric:</strong> 100% Pure Woven Cotton with Terry Fleece backing</li><li><strong>Protection:</strong> UPF 50+ Sun Blocking Brim</li><li><strong>Fastening:</strong> Adjustable soft Velcro chin strap on hat</li></ul><h4>Care Instructions</h4><p>Machine wash cold. Air dry.</p>',
                'seo_tags' => 'baby sun hat, drool bib, toddler accessories, baby gifts, baby gear',
            ],
        ];

        // Ensure we take up to 3 colors and 3 sizes per product for realistic variations
        $paletteColors = $colors->take(3);
        $standardSizes = $sizes->take(4);

        foreach ($productsData as $prod) {
            $cat = $categories->get($prod['category']);
            $catId = $cat ? $cat->id : 1;

            $subCat = null;
            if ($cat && $cat->subCategories) {
                $subCat = $cat->subCategories->firstWhere('name', $prod['subcategory']);
            }
            $subCatId = $subCat ? $subCat->id : null;

            $purchasePrice = round($prod['price'] * 0.65, 2);

            $product = Product::updateOrCreate(
                ['id' => $prod['id']],
                [
                    'name' => $prod['name'],
                    'slug' => Str::slug($prod['name']),
                    'category_id' => $catId,
                    'sub_category_id' => $subCatId,
                    'brand_id' => $brand->id,
                    'short_description' => $prod['short_description'],
                    'description' => $prod['description'],
                    'purchase_price' => $purchasePrice,
                    'sale_price' => $prod['price'],
                    'seo_title' => $prod['name'] . ' | Buzz Bangladesh',
                    'seo_description' => $prod['short_description'],
                    'seo_tags' => $prod['seo_tags'],
                    'active_status' => 1,
                    'is_new_arrival' => $prod['is_new_arrival'],
                    'is_featured' => $prod['is_featured'],
                    'is_best_seller' => $prod['is_best_seller'],
                    'is_on_sale' => $prod['is_on_sale'],
                    'is_trending' => $prod['is_trending'],
                ]
            );

            // Clean primary placeholder image
            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'is_main' => 1],
                [
                    'image_path' => 'products/placeholder.png',
                    'sort_order' => 0,
                    'product_color_id' => null,
                ]
            );

            // Create Variations & Stock
            foreach ($paletteColors as $color) {
                foreach ($standardSizes as $size) {
                    $sku = 'BUZZ-' . str_pad($product->id, 3, '0', STR_PAD_LEFT) . '-' . strtoupper(substr($color->name, 0, 3)) . '-' . $size->name;

                    $variation = ProductVariation::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'product_color_id' => $color->id,
                            'product_size_id' => $size->id,
                        ],
                        [
                            'sku' => $sku,
                            'purchase_price' => $product->purchase_price,
                            'sale_price' => $product->sale_price,
                            'stock_quantity' => 20,
                        ]
                    );

                    StockLedger::firstOrCreate(
                        [
                            'product_id' => $product->id,
                            'product_variation_id' => $variation->id,
                        ],
                        [
                            'quantity_added' => 20,
                            'purchase_price' => $product->purchase_price,
                            'note' => 'Initial demo stock allocation',
                            'created_by' => 1,
                        ]
                    );
                }
            }
        }
    }
}
