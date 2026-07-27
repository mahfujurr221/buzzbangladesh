<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us', 
                'content' => '<h2>Why Buzz Bangladesh exists</h2><p>Buzz Bangladesh exists to deliver premium-quality and contemporary designs for style-conscious urbanites. The brand name reflects warmth, loyalty, and long-lasting connections. With a promise of versatile style, superior comfort, and a joyful shopping experience, Buzz Bangladesh continues to brighten the everyday wardrobes of modern city life.</p><p>Trade License Number: TRAD/DNCC/025983/2026</p>'
            ],
            [
                'title' => 'Delivery Policy', 
                'content' => '<h2>Delivery Policy</h2><p>At <strong>Buzz Bangladesh</strong>, we ensure your orders are delivered as quickly as possible. After placing your order, you’ll receive a confirmation email.</p><p>Expect your delivery within 5-8 business days anywhere in Bangladesh, with no shipping fees. However, due to the increased rush of orders, deliveries during a sale campaign may take 8–10 business days.</p><p>We start processing your order within 24 hours. If you need to cancel or change it, please contact our Customer Services Department right away at 01958227060 or info@buzzbangladesh.com. When your order arrives, you can check the products before receiving. If there’s any issue, reach out to Customer Services Department for an immediate return.</p><h3>Please note:</h3><ul><li>Partial delivery/exchange are not allowed.</li><li>No exchanges for discounted/special offer items.</li><li>Accessories items such as Boxer briefs, Tank Tops, Leggings, & Pajama are not returnable and to be paid in advance.</li><li>Monetary refunds are not available.</li></ul>'
            ],
            [
                'title' => 'Privacy Policy', 
                'content' => '<h2>Privacy Policy</h2><p>The terms "we", "our", and "us" used in this policy refer to <strong>Buzz Bangladesh</strong> and <strong>buzzbangladesh.com</strong>.</p><p>We are committed to protecting the privacy of our customers. This privacy policy makes you able to understand what information we may collect from you when you visit buzzbangladesh.com and its subpages. It also clarifies, how we use such information and the choices you have with respect to our use of this information.</p><h3>What information we collect</h3><p>When you visit buzzbangladesh.com and its subpages, place an order, make a purchase, contact us or participate in any activities we conduct, we collect your identifiable information, viz.: name, email address, phone number, etc. We also maintain records of your history and interests to improve your shopping experience.</p><h3>How we use information we collect</h3><p>We use your identifiable information to help us learn more about your shopping preferences and to provide you with the best possible products and services. In this regard, we may share your identifiable information with third-parties that provide us support services or help us market Buzz Bangladesh products and services. Third-parties are contractually prohibited from using your identifiable information in any manner other than helping Buzz Bangladesh. We may share your personal information if necessary to comply with laws, government requests or to protect the rights of Buzz Bangladesh.</p><p>We may use your identifiable information to send periodic emails to provide you with information and updates regarding Buzz Bangladesh new arrivals, campaigns, and any other activities. However, if you prefer to no longer receive Buzz Bangladesh emails, you can unsubscribe following the instructions at the bottom of each of our emails.</p><h3>Usage of "cookies"</h3><p>We use "cookie" technology that allows our buzzbangladesh.com to recognize your browser, distinguish you from other customers, and enhance and personalize your online shopping experience. Cookies help us remember and process the items in your shopping cart, understand and save your preferences for future visits, and compile aggregate data about site traffic and site interaction so that we can improve our website design, products, services and campaigns. In this case also, third-parties are contractually prohibited from using your information of browsing history and product interest in any manner other than helping Buzz Bangladesh.</p><p>If you prefer, you can change the settings on your browser to prevent cookies being stored. This may, however, may prevent you from taking full advantage of buzzbangladesh.com.</p><h3>Third-party links</h3><p>buzzbangladesh.com may contain links to/from the websites of our parent brand, sister brands, partners, social media sites, and other third parties. If you follow a link to any of these websites, please note that they have their own privacy policies. We, therefore, have no responsibility or liability for the content and activities of these linked sites. Please check their policies before you submit any personal data on their websites.</p><h3>Questions</h3><p>For any concerns regarding this policy you can contact us via email at info@buzzbangladesh.com.</p>'
            ],
            [
                'title' => 'Refund and Return Policy', 
                'content' => '<h2>Refund and Return Policy</h2><p><strong>Returns</strong><br>Our policy lasts 30 days. If 30 days have gone by since your purchase, unfortunately we can’t offer you a refund or exchange.</p><p>To be eligible for a return, your item must be unused and in the same condition that you received it. It must also be in the original packaging.</p><p><strong>Refunds (if applicable)</strong><br>Once your return is received and inspected, we will send you an email to notify you that we have received your returned item. We will also notify you of the approval or rejection of your refund. If you are approved, then your refund will be processed, and a credit will automatically be applied to your credit card or original method of payment, within a certain amount of days.</p><p><strong>Exchanges (if applicable)</strong><br>We only replace items if they are defective or damaged. If you need to exchange it for the same item, send us an email at info@buzzbangladesh.com.</p>'
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => Str::slug($page['title'])],
                [
                    'title' => $page['title'],
                    'content' => $page['content'],
                    'status' => 1
                ]
            );
        }
    }
}
