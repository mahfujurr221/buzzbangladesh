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
                'content' => '<p><strong>Welcome to Buzz Bangladesh</strong>, your number one source for premium quality products. We\'re dedicated to giving you the very best of products, with a focus on dependability, customer service and uniqueness.</p><p>Founded in 2026, Buzz Bangladesh has come a long way from its beginnings in Dhaka. When our founder first started out, their passion for providing the best shopping experience drove them to do intense research, and gave them the impetus to turn hard work and inspiration into to a booming online store. We now serve customers all over Bangladesh, and are thrilled to be a part of the booming e-commerce industry.</p><p>We hope you enjoy our products as much as we enjoy offering them to you. If you have any questions or comments, please don\'t hesitate to contact us.</p>'
            ],
            [
                'title' => 'Terms and Conditions', 
                'content' => '<p><strong>Welcome to Buzz Bangladesh!</strong></p><p>These terms and conditions outline the rules and regulations for the use of Buzz Bangladesh\'s Website, located at www.buzzbangladesh.com.</p><p>By accessing this website we assume you accept these terms and conditions. Do not continue to use Buzz Bangladesh if you do not agree to take all of the terms and conditions stated on this page.</p><ul><li><strong>Cookies:</strong> We employ the use of cookies. By accessing Buzz Bangladesh, you agreed to use cookies in agreement with the Buzz Bangladesh\'s Privacy Policy.</li><li><strong>License:</strong> Unless otherwise stated, Buzz Bangladesh and/or its licensors own the intellectual property rights for all material on Buzz Bangladesh. All intellectual property rights are reserved. You may access this from Buzz Bangladesh for your own personal use subjected to restrictions set in these terms and conditions.</li></ul><p>You must not:</p><ul><li>Republish material from Buzz Bangladesh</li><li>Sell, rent or sub-license material from Buzz Bangladesh</li><li>Reproduce, duplicate or copy material from Buzz Bangladesh</li><li>Redistribute content from Buzz Bangladesh</li></ul>'
            ],
            [
                'title' => 'Privacy Policy', 
                'content' => '<p>At <strong>Buzz Bangladesh</strong>, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by Buzz Bangladesh and how we use it.</p><h3>Information we collect</h3><p>The personal information that you are asked to provide, and the reasons why you are asked to provide it, will be made clear to you at the point we ask you to provide your personal information.</p><p>If you contact us directly, we may receive additional information about you such as your name, email address, phone number, the contents of the message and/or attachments you may send us, and any other information you may choose to provide.</p><h3>How we use your information</h3><p>We use the information we collect in various ways, including to:</p><ul><li>Provide, operate, and maintain our website</li><li>Improve, personalize, and expand our website</li><li>Understand and analyze how you use our website</li><li>Develop new products, services, features, and functionality</li><li>Communicate with you, either directly or through one of our partners, including for customer service, to provide you with updates and other information relating to the website, and for marketing and promotional purposes</li><li>Send you emails</li><li>Find and prevent fraud</li></ul>'
            ],
            [
                'title' => 'Refund and Return Policy', 
                'content' => '<p><strong>Returns</strong><br>Our policy lasts 30 days. If 30 days have gone by since your purchase, unfortunately we can’t offer you a refund or exchange.</p><p>To be eligible for a return, your item must be unused and in the same condition that you received it. It must also be in the original packaging.</p><p><strong>Refunds (if applicable)</strong><br>Once your return is received and inspected, we will send you an email to notify you that we have received your returned item. We will also notify you of the approval or rejection of your refund. If you are approved, then your refund will be processed, and a credit will automatically be applied to your credit card or original method of payment, within a certain amount of days.</p><p><strong>Exchanges (if applicable)</strong><br>We only replace items if they are defective or damaged. If you need to exchange it for the same item, send us an email at info@buzzbangladesh.com.</p>'
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
