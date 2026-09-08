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
                'title' => 'Delivery Policy',
                'slug'  => 'delivery-policy',
                'content' => <<<HTML
<div class="policy-en">
    <h2>Delivery Policy</h2>
    <p>At <strong>Buzz Bangladesh</strong>, we ensure your orders are delivered as quickly as possible. After placing your order, you’ll receive a confirmation email.</p>
    <p>Expect your delivery within 5-8 business days anywhere in Bangladesh, with no shipping fees. However, due to the increased rush of orders, deliveries during a sale campaign may take 8–10 business days.</p>
    <p>We start processing your order within 24 hours. If you need to cancel or change it, please contact our Customer Services Department right away at <strong>01958227060</strong> or <a href="mailto:info@buzzbangladesh.com">info@buzzbangladesh.com</a>. When your order arrives, you can check the products before receiving. If there’s any issue, reach out to Customer Services Department for an immediate return.</p>
    <h3>Please note:</h3>
    <ul>
        <li>Partial delivery/exchange are not allowed.</li>
        <li>No exchanges for discounted/special offer items.</li>
        <li>No alteration services provided for online orders.</li>
        <li>Accessories items such as boxer briefs, tank tops, leggings, &amp; pajamas are not returnable and to be paid in advance.</li>
        <li>Monetary refunds are not available.</li>
    </ul>
</div>

<hr style="margin: 35px 0; border: 0; border-top: 1px dashed #e2e8f0;">

<div class="policy-bn">
    <h2>ডেলিভারি পলিসি</h2>
    <p>আপনার অর্ডারটি সময়মতো পৌঁছে দিতে আমরা সর্বদাই প্রতিশ্রুতিবদ্ধ। অর্ডার দেওয়ার পর, আপনি একটি কনফার্মেশন ইমেইল পাবেন।</p>
    <p>বাংলাদেশের যেকোনো স্থানে ৫ থেকে ৮ কার্যদিবসের মধ্যে আপনার অর্ডারটি সম্পূর্ণ বিনামূল্যে পৌঁছে দিতে আমরা সর্বাত্মক প্রচেষ্টা করি। তবে সেল ক্যাম্পেইন চলাকালীন সময়ে অর্ডার ডেলিভারির সময় ৮ থেকে ১০ কার্যদিবস হতে পারে।</p>
    <p>২৪ ঘণ্টার মধ্যে আমাদের অর্ডার প্রক্রিয়াকরণ শুরু হয়। কোনো অর্ডার বাতিল বা পরিবর্তন করতে চাইলে, দয়া করে দ্রুততম সময়ে কাস্টমার সার্ভিসে (<strong>01958227060</strong> / <a href="mailto:info@buzzbangladesh.com">info@buzzbangladesh.com</a>) যোগাযোগ করুন। অর্ডারটি হাতে পাওয়ার পর, রিসিভ করার আগে আপনি চেক করে নিতে পারবেন। কোনো সমস্যা হলে, সাথে সাথে কাস্টমার সার্ভিসে জানান এবং রিটার্ন করুন।</p>
    <h3>উল্লেখ্য:</h3>
    <ul>
        <li>আংশিক ডেলিভারি/পরিবর্তন গ্রহণযোগ্য নয়।</li>
        <li>কোনো ছাড় বা বিশেষ-অফারের প্রোডাক্ট পরিবর্তনযোগ্য নয়।</li>
        <li>অনলাইন অর্ডারের ক্ষেত্রে অল্টারেশন (টেইলরিং) সার্ভিস দেওয়া হয় না।</li>
        <li>এক্সেসরিজ আইটেম যেমন বক্সার ব্রিফ, ট্যাঙ্ক টপ, লেগিংস এবং পাজামা ফেরতযোগ্য নয় এবং পূর্ণমূল্য পূর্বপরিশোধযোগ্য।</li>
        <li>ক্রয়কৃত প্রোডাক্টের বিনিময়ে কোনো আর্থিক মূল্য অফেরতযোগ্য।</li>
    </ul>
</div>
HTML
            ],
            [
                'title' => 'Privacy Policy',
                'slug'  => 'privacy-policy',
                'content' => <<<HTML
<div class="policy-en">
    <h2>Privacy Policy</h2>
    <p>The terms "we", "our", and "us" used in this policy refer to <strong>Buzz Bangladesh</strong> and <a href="https://buzzbangladesh.com">buzzbangladesh.com</a>.</p>
    <p>We are committed to protecting the privacy of our customers. This privacy policy makes you able to understand what information we may collect from you when you visit <a href="https://buzzbangladesh.com">buzzbangladesh.com</a> and its subpages. It also clarifies, how we use such information and the choices you have with respect to our use of this information.</p>
    
    <h3>What information we collect</h3>
    <p>When you visit <a href="https://buzzbangladesh.com">buzzbangladesh.com</a> and its subpages, place an order, make a purchase, contact us or participate in any activities we conduct, we collect your identifiable information, viz.: name, email address, phone number, etc. We also maintain records of your history and interests to improve your shopping experience.</p>
    
    <h3>How we use information we collect</h3>
    <p>We use your identifiable information to help us learn more about your shopping preferences and to provide you with the best possible products and services. In this regard, we may share your identifiable information with third-parties that provide us support services or help us market Buzz Bangladesh products and services. Third-parties are contractually prohibited from using your identifiable information in any manner other than helping Buzz Bangladesh. We may share your personal information if necessary to comply with laws, government requests or to protect the rights of Buzz Bangladesh.</p>
    <p>We may use your identifiable information to send periodic emails to provide you with information and updates regarding Buzz Bangladesh new arrivals, campaigns, and any other activities. However, if you prefer to no longer receive Buzz Bangladesh emails, you can unsubscribe following the instructions at the bottom of each of our emails.</p>
    
    <h3>Usage of "cookies"</h3>
    <p>We use "cookie" technology that allows our <a href="https://buzzbangladesh.com">buzzbangladesh.com</a> to recognize your browser, distinguish you from other customers, and enhance and personalize your online shopping experience. Cookies help us remember and process the items in your shopping cart, understand and save your preferences for future visits, and compile aggregate data about site traffic and site interaction so that we can improve our website design, products, services and campaigns. In this case also, third-parties are contractually prohibited from using your information of browsing history and product interest in any manner other than helping Buzz Bangladesh.</p>
    <p>If you prefer, you can change the settings on your browser to prevent cookies being stored. This may, however, prevent you from taking full advantage of <a href="https://buzzbangladesh.com">buzzbangladesh.com</a>.</p>
    
    <h3>Third-party links</h3>
    <p><a href="https://buzzbangladesh.com">buzzbangladesh.com</a> may contain links to/from the websites of our parent brand, sister brands, partners, social media sites, and other third parties. If you follow a link to any of these websites, please note that they have their own privacy policies. We, therefore, have no responsibility or liability for the content and activities of these linked sites. Please check their policies before you submit any personal data on their websites.</p>
    
    <h3>Questions</h3>
    <p>For any concerns regarding this policy you can contact us via email at <a href="mailto:info@buzzbangladesh.com">info@buzzbangladesh.com</a>.</p>
</div>

<hr style="margin: 35px 0; border: 0; border-top: 1px dashed #e2e8f0;">

<div class="policy-bn">
    <h2>প্রাইভেসি পলিসি</h2>
    <p>এই পলিসিতে ব্যবহৃত "আমরা", "আমাদের", এবং "আমাদেরকে" শব্দগুলো <strong>Buzz Bangladesh</strong> ব্র্যান্ড এবং <a href="https://buzzbangladesh.com">buzzbangladesh.com</a> ওয়েবসাইট কে বোঝায়। আমরা আমাদের গ্রাহকদের গোপনীয়তা রক্ষায় প্রতিশ্রুতিবদ্ধ। <a href="https://buzzbangladesh.com">buzzbangladesh.com</a> আপনার কাছ থেকে কী তথ্য সংগ্রহ করতে পারে তা এই প্রাইভেসি পলিসি থেকে আপনি বুঝতে পারবেন, আরও জানতে পারবেন, আমরা কি কাজে এসব তথ্য ব্যবহার করি এবং ব্যবহারের ক্ষেত্রে আপনার জন্য আর কী কী বিকল্প ব্যবস্থা রয়েছে।</p>
    
    <h3>আমরা কোন তথ্য সংগ্রহ করি:</h3>
    <p>যখন আপনি <a href="https://buzzbangladesh.com">buzzbangladesh.com</a> এবং এর অন্তর্ভুক্ত ওয়েবপেজগুলোতে যান, অর্ডার দেন, কেনাকাটা করেন, আমাদের সাথে যোগাযোগ করেন বা আমাদের দ্বারা পরিচালিত যেকোন কার্যকলাপে অংশ নেন, তখন আমরা আপনার পরিচয়যোগ্য তথ্য সংগ্রহ করি, যেমন: নাম, ইমেইল ঠিকানা, ফোন নম্বর ইত্যাদি। আমরা আপনার কেনাকাটার অভিজ্ঞতা আরও মানসম্পন্ন করতে আপনার পূর্ব কেনাকাটার ইতিহাস এবং পছন্দের রেকর্ডও সংরক্ষণ করে থাকি।</p>
    
    <h3>আমরা যে তথ্য সংগ্রহ করি তা কিভাবে ব্যবহার করি:</h3>
    <p>আমরা আপনার পরিচয়যোগ্য তথ্য ব্যবহার করি যাতে আমরা আপনার কেনাকাটার পছন্দ সম্পর্কে আরও জানতে পারি এবং আপনাকে সবচেয়ে ভালো মানের পণ্য ও পরিষেবা প্রদান করতে পারি। সেই জন্য আমরা আপনার তথ্য থার্ড পার্টির সাথে শেয়ার করতে পারি যারা আমাদের পরিষেবা প্রদান করে বা Buzz Bangladesh-এর পণ্য ও পরিষেবা বিপণনে সহায়তা করে। থার্ড পার্টিদের চুক্তিগতভাবে Buzz Bangladesh কে সহায়তা করা ছাড়া অন্য কোনভাবে আপনার পরিচয়যোগ্য তথ্য ব্যবহার করতে নিষেধ করা হয়েছে। আমরা আইন ও সরকারী অনুরোধের সাথে সম্মতি জানাতে বা Buzz Bangladesh-এর অধিকার রক্ষা করার জন্য প্রয়োজনে আপনার ব্যক্তিগত তথ্য শেয়ার করতে পারি।</p>
    <p>আমরা Buzz Bangladesh-এর নতুন পণ্য, প্রচারণা এবং যেকোনো অন্যান্য কার্যক্রম সম্পর্কে আপনাকে তথ্য এবং আপডেট প্রদান করতে আপনার তথ্য ব্যবহার করতে পারি। তবে, আপনি যদি আর Buzz Bangladesh-এর ইমেইল পেতে না চান, তবে আমাদের প্রতিটি ইমেইলের নিচে থাকা নির্দেশাবলী অনুসরণ করে আপনি সদস্যতা বাতিল করতে পারবেন।</p>
    
    <h3>"কুকিজ" এর ব্যবহার:</h3>
    <p>আমরা "কুকি" প্রযুক্তি ব্যবহার করি যা আমাদের <a href="https://buzzbangladesh.com">buzzbangladesh.com</a> কে আপনার ব্রাউজার চিনতে, আপনাকে অন্যান্য গ্রাহকদের থেকে আলাদা করতে এবং আপনার অনলাইন কেনাকাটার অভিজ্ঞতাকে উন্নত ও আরও নিজস্বকরণ করতে সহায়তা করে। কুকিজ আমাদের আপনার শপিং কার্টে থাকা আইটেমগুলি মনে রাখতে এবং প্রক্রিয়া করতে, ভবিষ্যতের জন্য আপনার পছন্দগুলি বুঝতে ও সংরক্ষণ করতে, এবং সাইটের ট্র্যাফিক এবং সাইটের ইন্টারঅ্যাকশন সম্পর্কে সামগ্রিক তথ্য সংকলন করতে সহায়তা করে যাতে আমরা আমাদের ওয়েবসাইটের ডিজাইন, পণ্য, পরিষেবা এবং প্রচারণা সুষ্ঠুভাবে পরিচালনা করতে পারি; এই ক্ষেত্রেও, থার্ড পার্টিদের চুক্তিগতভাবে ব্রাউজিং হিস্ট্রি এবং পণ্যের আগ্রহের তথ্য ব্যবহার অথবা অন্য কোন উপায়ে Buzz Bangladesh কে সহায়তা করা নিষিদ্ধ।</p>
    <p>আপনি যদি চান, আপনি ব্রাউজারে সেটিংস পরিবর্তন করে কুকিজ সংরক্ষণ বন্ধ করতে পারেন। তবে, এতে হয়তো আপনি <a href="https://buzzbangladesh.com">buzzbangladesh.com</a>-এর পূর্ণ সুবিধা নিতে পারবেন না।</p>
    
    <h3>তৃতীয় পক্ষের লিঙ্ক:</h3>
    <p><a href="https://buzzbangladesh.com">buzzbangladesh.com</a>-এ আমাদের প্যারেন্ট ব্র্যান্ড, সিস্টার ব্র্যান্ড, অংশীদার, সোশ্যাল মিডিয়া সাইট এবং অন্যান্য থার্ড পার্টি ওয়েবসাইটের লিঙ্ক থাকতে পারে। আপনি যদি এই ওয়েবসাইটগুলির কোন একটি লিঙ্ক অনুসরণ করেন, তবে দয়া করে মনে রাখবেন যে তাদের নিজস্ব গোপনীয়তা নীতিমালা রয়েছে। অতএব, এই লিঙ্কযুক্ত সাইটগুলির বিষয়বস্তু এবং কার্যকলাপের জন্য আমাদের কোন দায়বদ্ধতা নেই। অনুগ্রহ করে তাদের ওয়েবসাইটে যেকোনো ব্যক্তিগত তথ্য জমা দেওয়ার আগে তাদের নীতিমালা পরীক্ষা করে নিন।</p>
    
    <h3>প্রশ্ন:</h3>
    <p>এই পলিসি সম্পর্কে যেকোন প্রশ্ন থাকলে আপনি <a href="mailto:info@buzzbangladesh.com">info@buzzbangladesh.com</a> - এই ই-মেইলের মাধ্যমে আমাদের সাথে যোগাযোগ করতে পারেন।</p>
</div>
HTML
            ],
            [
                'title' => 'Exchange Policy',
                'slug'  => 'exchange-policy',
                'content' => <<<HTML
<div class="policy-en">
    <h2>Exchange Policy</h2>
    <p><strong>Buzz Bangladesh</strong>'s exchange policy allows you to exchange any Buzz Bangladesh product purchased online within 15 days of receipt, free of charge. Simply ensure the product is unused and maintain its original condition, tags, and packaging. Exchanges are subject to stock availability.</p>
    <p>To initiate an exchange, contact our Customer Services Department at <a href="mailto:info@buzzbangladesh.com">info@buzzbangladesh.com</a> or <strong>01958227060</strong>. If you opt for an exchange, return the full parcel to our online store address stated on the top right corner of our invoice and notify our customer service team. We'll promptly process your request and resend the full order.</p>
    <p>You can also exchange products at any Buzz Bangladesh store. For this, please visit any of our outlets with the product you want to exchange along with the invoice.</p>
    <h3>Please note:</h3>
    <ul>
        <li>Exchange can be availed only once against an invoice.</li>
        <li>Products purchased on discounts/special offers cannot be exchanged or refunded.</li>
        <li>No alteration services provided for online orders.</li>
        <li>No exchanges for pajama, luxury saree, leggings, dupatta or scarves, innerwear, home products, and accessories.</li>
        <li>Monetary refunds are not available.</li>
    </ul>
</div>

<hr style="margin: 35px 0; border: 0; border-top: 1px dashed #e2e8f0;">

<div class="policy-bn">
    <h2>এক্সচেঞ্জ পলিসি</h2>
    <p>অনলাইনে কেনা <strong>Buzz Bangladesh</strong>-এর যেকোনো প্রোডাক্ট ক্রয়ের ১৫ দিনের মধ্যে সম্পূর্ণ বিনামূল্যে পরিবর্তন করা যাবে। এক্ষেত্রে, প্রোডাক্টটি অব্যবহৃত অবস্থায় ট্যাগ সহ মূল প্যাকেজিংয়ে রাখতে হবে এবং সাথে ক্রয়ের রসিদটি থাকতে হবে। সকল প্রোডাক্ট পরিবর্তন স্টক-লভ্যতার ওপর নির্ভরশীল।</p>
    <p>ক্রয়কৃত প্রোডাক্ট পরিবর্তন করতে চাইলে আমাদের কাস্টমার সার্ভিস বিভাগে (<a href="mailto:info@buzzbangladesh.com">info@buzzbangladesh.com</a> / <strong>01958227060</strong>)-এ যোগাযোগ করুন এবং সম্পূর্ণ পার্সেলটি আমাদের ইনভয়েসে উল্লেখিত Buzz Bangladesh অনলাইন স্টোরের ঠিকানায় পাঠিয়ে দিন। আমরা আপনার অর্ডারটি পরিবর্তন করে পুনরায় আপনার ঠিকানায় পৌঁছে দিবো।</p>
    <p>আপনি চাইলে, যেকোনো Buzz Bangladesh স্টোর থেকেও প্রোডাক্ট পরিবর্তন করতে পারেন। সেক্ষেত্রে, ইনভয়েসসহ আপনার পণ্যটি নিয়ে আমাদের যেকোনো স্টোরে ভিজিট করুন।</p>
    <h3>উল্লেখ্য:</h3>
    <ul>
        <li>১ টি ইনভয়েস এর বিপরীতে কেবল ১ বার পরিবর্তন প্রযোজ্য হবে।</li>
        <li>কোনো ছাড় বা বিশেষ-অফারের প্রোডাক্ট পরিবর্তনযোগ্য নয়।</li>
        <li>অনলাইন অর্ডারের ক্ষেত্রে অল্টারেশন (টেইলরিং) সার্ভিস দেওয়া হয় না।</li>
        <li>পায়জামা, লাক্সারি শাড়ি, লেগিংস, দুপাট্টা, স্কার্ফ, আন্ডারগার্মেন্ট, হোম প্রোডাক্ট ও এক্সেসরি পরিবর্তনযোগ্য নয়।</li>
        <li>ক্রয়কৃত প্রোডাক্টের বিনিময়ে কোনো আর্থিক মূল্য অফেরতযোগ্য।</li>
    </ul>
</div>
HTML
            ],
            [
                'title' => 'Refund and Return Policy',
                'slug'  => 'refund-and-return-policy',
                'content' => <<<HTML
<div class="policy-en">
    <h2>Refund &amp; Return Policy</h2>
    <p>At <strong>Buzz Bangladesh</strong>, we uphold a dedicated Exchange Policy in lieu of monetary refunds.</p>
    <p>Our exchange policy allows you to exchange any Buzz Bangladesh product purchased online within 15 days of receipt, free of charge. Simply ensure the product is unused and maintain its original condition, tags, and packaging.</p>
    <p>To initiate an exchange, contact our Customer Services Department at <a href="mailto:info@buzzbangladesh.com">info@buzzbangladesh.com</a> or <strong>01958227060</strong>. Return the parcel to the address specified on your invoice.</p>
    <h3>Important Terms:</h3>
    <ul>
        <li>Exchange can be availed only once against an invoice.</li>
        <li>Products purchased on discounts/special offers cannot be exchanged or refunded.</li>
        <li>No alteration services provided for online orders.</li>
        <li>No exchanges for pajama, luxury saree, leggings, dupatta or scarves, innerwear, home products, and accessories.</li>
        <li>Monetary refunds are not available.</li>
    </ul>
</div>

<hr style="margin: 35px 0; border: 0; border-top: 1px dashed #e2e8f0;">

<div class="policy-bn">
    <h2>রিফান্ড ও রিটার্ন পলিসি</h2>
    <p><strong>Buzz Bangladesh</strong>-এ আর্থিক রিফান্ডের পরিবর্তে পণ্য পরিবর্তনের (Exchange Policy) সুবিধা প্রদান করা হয়।</p>
    <p>অনলাইনে কেনা Buzz Bangladesh-এর যেকোনো প্রোডাক্ট ক্রয়ের ১৫ দিনের মধ্যে সম্পূর্ণ বিনামূল্যে পরিবর্তন করা যাবে। এক্ষেত্রে প্রোডাক্টটি অব্যবহৃত অবস্থায় ট্যাগ সহ মূল প্যাকেজিংয়ে রাখতে হবে।</p>
    <p>পণ্য পরিবর্তন করতে চাইলে আমাদের কাস্টমার সার্ভিসে (<a href="mailto:info@buzzbangladesh.com">info@buzzbangladesh.com</a> / <strong>01958227060</strong>) যোগাযোগ করুন।</p>
    <h3>জরুরি নিয়মাবলী:</h3>
    <ul>
        <li>১ টি ইনভয়েস এর বিপরীতে কেবল ১ বার পরিবর্তন প্রযোজ্য হবে।</li>
        <li>কোনো ছাড় বা বিশেষ-অফারের প্রোডাক্ট পরিবর্তন বা রিফান্ডযোগ্য নয়।</li>
        <li>ক্রয়কৃত প্রোডাক্টের বিনিময়ে কোনো আর্থিক মূল্য অফেরতযোগ্য।</li>
    </ul>
</div>
HTML
            ],
            [
                'title' => 'About Us',
                'slug'  => 'about-us',
                'content' => <<<HTML
<div class="about-us-content">
    <h2>Why Buzz Bangladesh Exists</h2>
    <p><strong>Buzz Bangladesh</strong> exists to deliver premium-quality and contemporary designs for style-conscious urbanites. The brand name reflects warmth, loyalty, and long-lasting connections. With a promise of versatile style, superior comfort, and a joyful shopping experience, Buzz Bangladesh continues to brighten the everyday wardrobes of modern city life.</p>
    
    <div style="margin-top: 30px; padding: 18px 24px; background: #f8fafc; border-left: 4px solid #9A0002; border-radius: 6px;">
        <p style="margin: 0; font-size: 15px; font-weight: 600; color: #1e293b;">
            Trade License Number: <span style="color: #9A0002; font-weight: 700; letter-spacing: 0.5px;">TRAD/DNCC/016842/2025</span>
        </p>
    </div>
</div>
HTML
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title'   => $page['title'],
                    'content' => $page['content'],
                    'status'  => 1,
                ]
            );
        }
    }
}
