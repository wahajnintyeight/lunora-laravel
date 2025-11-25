<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => $this->getAboutUsContent(),
                'meta_data' => json_encode([
                    'title' => 'About Lunora - Premium Jewelry Store',
                    'description' => 'Learn about Lunora, Pakistan\'s premier jewelry destination offering exquisite rings, necklaces, earrings, and more.',
                ]),
                'is_published' => true,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => $this->getPrivacyPolicyContent(),
                'meta_data' => json_encode([
                    'title' => 'Privacy Policy - Lunora',
                    'description' => 'Read our privacy policy to understand how we collect, use, and protect your personal information.',
                ]),
                'is_published' => true,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => $this->getTermsOfServiceContent(),
                'meta_data' => json_encode([
                    'title' => 'Terms of Service - Lunora',
                    'description' => 'Read our terms of service to understand the conditions for using our website and services.',
                ]),
                'is_published' => true,
            ],
            [
                'title' => 'Shipping & Returns',
                'slug' => 'shipping-returns',
                'content' => $this->getShippingReturnsContent(),
                'meta_data' => json_encode([
                    'title' => 'Shipping & Returns - Lunora',
                    'description' => 'Learn about our shipping options, delivery times, and return policy.',
                ]),
                'is_published' => true,
            ],
            [
                'title' => 'Size Guide',
                'slug' => 'size-guide',
                'content' => $this->getSizeGuideContent(),
                'meta_data' => json_encode([
                    'title' => 'Jewelry Size Guide - Lunora',
                    'description' => 'Find the perfect fit with our comprehensive jewelry size guide for rings, bracelets, and necklaces.',
                ]),
                'is_published' => true,
            ],
            [
                'title' => 'Care Instructions',
                'slug' => 'care-instructions',
                'content' => $this->getCareInstructionsContent(),
                'meta_data' => json_encode([
                    'title' => 'Jewelry Care Instructions - Lunora',
                    'description' => 'Learn how to properly care for and maintain your precious jewelry to keep it looking beautiful.',
                ]),
                'is_published' => true,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'content' => $this->getContactUsContent(),
                'meta_data' => json_encode([
                    'title' => 'Contact Us - Lunora',
                    'description' => 'Get in touch with Lunora for any questions about our jewelry, orders, or services.',
                ]),
                'is_published' => true,
            ]
        ];

        foreach ($pages as $pageData) {
            Page::firstOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        $this->command->info('Static pages created successfully!');
    }

    private function getAboutUsContent(): string
    {
        return '<div class="prose max-w-none">
            <h2>Welcome to Lunora</h2>
            <p>Lunora is Pakistan\'s premier destination for exquisite jewelry, offering a carefully curated collection of rings, necklaces, earrings, bracelets, and watches. Since our founding, we have been committed to providing our customers with the finest quality jewelry crafted with precision and passion.</p>
            
            <h3>Our Story</h3>
            <p>Founded with a vision to make luxury jewelry accessible to everyone, Lunora combines traditional craftsmanship with modern design sensibilities. Our team of skilled artisans and designers work tirelessly to create pieces that celebrate life\'s most precious moments.</p>
            
            <h3>Quality & Craftsmanship</h3>
            <p>Every piece in our collection is carefully selected and crafted using the finest materials including 18k and 22k gold, sterling silver, and genuine gemstones. We believe that jewelry should not only be beautiful but also durable enough to be treasured for generations.</p>
            
            <h3>Our Promise</h3>
            <ul>
                <li>Authentic materials and genuine gemstones</li>
                <li>Expert craftsmanship and attention to detail</li>
                <li>Competitive pricing without compromising quality</li>
                <li>Exceptional customer service and support</li>
                <li>Secure shopping and reliable delivery</li>
            </ul>
            
            <h3>Customer Satisfaction</h3>
            <p>Your satisfaction is our top priority. We offer a comprehensive warranty on all our products and provide excellent after-sales service. Our customer support team is always ready to assist you with any questions or concerns.</p>
        </div>';
    }

    private function getPrivacyPolicyContent(): string
    {
        return '<div class="prose max-w-none">
            <h2>Privacy Policy</h2>
            <p><strong>Last updated:</strong> ' . now()->format('F d, Y') . '</p>
            
            <h3>Information We Collect</h3>
            <p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us. This may include:</p>
            <ul>
                <li>Name, email address, and phone number</li>
                <li>Billing and shipping addresses</li>
                <li>Payment information (processed securely)</li>
                <li>Order history and preferences</li>
            </ul>
            
            <h3>How We Use Your Information</h3>
            <p>We use the information we collect to:</p>
            <ul>
                <li>Process and fulfill your orders</li>
                <li>Communicate with you about your orders</li>
                <li>Provide customer support</li>
                <li>Send you promotional emails (with your consent)</li>
                <li>Improve our website and services</li>
            </ul>
            
            <h3>Information Sharing</h3>
            <p>We do not sell, trade, or otherwise transfer your personal information to third parties except:</p>
            <ul>
                <li>To fulfill your orders (shipping companies)</li>
                <li>To process payments (payment processors)</li>
                <li>When required by law</li>
            </ul>
            
            <h3>Data Security</h3>
            <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
            
            <h3>Your Rights</h3>
            <p>You have the right to:</p>
            <ul>
                <li>Access your personal information</li>
                <li>Correct inaccurate information</li>
                <li>Request deletion of your information</li>
                <li>Opt-out of marketing communications</li>
            </ul>
            
            <h3>Contact Us</h3>
            <p>If you have any questions about this Privacy Policy, please contact us at privacy@lunora.com</p>
        </div>';
    }

    private function getTermsOfServiceContent(): string
    {
        return '<div class="prose max-w-none">
            <h2>Terms of Service</h2>
            <p><strong>Last updated:</strong> ' . now()->format('F d, Y') . '</p>
            
            <h3>Acceptance of Terms</h3>
            <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p>
            
            <h3>Products and Pricing</h3>
            <ul>
                <li>All prices are listed in Pakistani Rupees (PKR)</li>
                <li>Prices are subject to change without notice</li>
                <li>Product availability is subject to stock</li>
                <li>We reserve the right to limit quantities</li>
            </ul>
            
            <h3>Orders and Payment</h3>
            <ul>
                <li>All orders are subject to acceptance and availability</li>
                <li>Payment must be received before order processing</li>
                <li>We accept major credit cards and bank transfers</li>
                <li>Order confirmation will be sent via email</li>
            </ul>
            
            <h3>Shipping and Delivery</h3>
            <ul>
                <li>Delivery times are estimates and not guaranteed</li>
                <li>Risk of loss passes to you upon delivery</li>
                <li>Additional charges may apply for remote areas</li>
            </ul>
            
            <h3>Returns and Exchanges</h3>
            <ul>
                <li>Items may be returned within 30 days of delivery</li>
                <li>Items must be in original condition</li>
                <li>Custom or personalized items cannot be returned</li>
                <li>Return shipping costs are customer\'s responsibility</li>
            </ul>
            
            <h3>Warranty</h3>
            <p>We provide a limited warranty against manufacturing defects. This warranty does not cover normal wear, damage from misuse, or loss.</p>
            
            <h3>Limitation of Liability</h3>
            <p>Our liability is limited to the purchase price of the item. We are not liable for any indirect, incidental, or consequential damages.</p>
        </div>';
    }

    private function getShippingReturnsContent(): string
    {
        return '<div class="prose max-w-none">
            <h2>Shipping & Returns</h2>
            
            <h3>Shipping Information</h3>
            <h4>Delivery Areas</h4>
            <p>We currently deliver throughout Pakistan to all major cities and towns.</p>
            
            <h4>Shipping Costs</h4>
            <ul>
                <li>Standard Delivery: PKR 500 (3-5 business days)</li>
                <li>Express Delivery: PKR 1,000 (1-2 business days)</li>
                <li>Free shipping on orders above PKR 25,000</li>
            </ul>
            
            <h4>Processing Time</h4>
            <p>Orders are typically processed within 1-2 business days. Custom or personalized items may take 5-7 business days.</p>
            
            <h3>Returns Policy</h3>
            <h4>Return Window</h4>
            <p>You may return items within 30 days of delivery for a full refund or exchange.</p>
            
            <h4>Return Conditions</h4>
            <ul>
                <li>Items must be in original condition</li>
                <li>Original packaging and tags must be included</li>
                <li>Custom or engraved items cannot be returned</li>
                <li>Earrings cannot be returned for hygiene reasons</li>
            </ul>
            
            <h4>Return Process</h4>
            <ol>
                <li>Contact our customer service team</li>
                <li>Receive return authorization and instructions</li>
                <li>Package items securely and ship back</li>
                <li>Refund processed within 5-7 business days</li>
            </ol>
            
            <h3>Exchanges</h3>
            <p>We offer free exchanges for size or style within 30 days. The new item must be of equal or lesser value.</p>
            
            <h3>Damaged or Defective Items</h3>
            <p>If you receive a damaged or defective item, please contact us immediately. We will arrange for return shipping and provide a full refund or replacement.</p>
        </div>';
    }

    private function getSizeGuideContent(): string
    {
        return '<div class="prose max-w-none">
            <h2>Jewelry Size Guide</h2>
            
            <h3>Ring Sizing</h3>
            <h4>How to Measure Your Ring Size</h4>
            <ol>
                <li>Wrap a string or paper strip around your finger</li>
                <li>Mark where the string meets</li>
                <li>Measure the length in millimeters</li>
                <li>Use our size chart below to find your size</li>
            </ol>
            
            <h4>Ring Size Chart</h4>
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="border border-gray-300 px-4 py-2">Size</th>
                        <th class="border border-gray-300 px-4 py-2">Circumference (mm)</th>
                        <th class="border border-gray-300 px-4 py-2">Diameter (mm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="border border-gray-300 px-4 py-2">5</td><td class="border border-gray-300 px-4 py-2">49.3</td><td class="border border-gray-300 px-4 py-2">15.7</td></tr>
                    <tr><td class="border border-gray-300 px-4 py-2">6</td><td class="border border-gray-300 px-4 py-2">51.9</td><td class="border border-gray-300 px-4 py-2">16.5</td></tr>
                    <tr><td class="border border-gray-300 px-4 py-2">7</td><td class="border border-gray-300 px-4 py-2">54.4</td><td class="border border-gray-300 px-4 py-2">17.3</td></tr>
                    <tr><td class="border border-gray-300 px-4 py-2">8</td><td class="border border-gray-300 px-4 py-2">57.0</td><td class="border border-gray-300 px-4 py-2">18.1</td></tr>
                    <tr><td class="border border-gray-300 px-4 py-2">9</td><td class="border border-gray-300 px-4 py-2">59.5</td><td class="border border-gray-300 px-4 py-2">18.9</td></tr>
                </tbody>
            </table>
            
            <h3>Bracelet Sizing</h3>
            <h4>How to Measure Your Wrist</h4>
            <ol>
                <li>Wrap a measuring tape around your wrist</li>
                <li>Add 1-2 cm for comfortable fit</li>
                <li>For snug fit, add 1 cm</li>
                <li>For loose fit, add 2 cm</li>
            </ol>
            
            <h3>Necklace Length Guide</h3>
            <ul>
                <li><strong>14-16 inches:</strong> Choker length, sits at base of neck</li>
                <li><strong>18 inches:</strong> Princess length, sits at collarbone</li>
                <li><strong>20-22 inches:</strong> Matinee length, sits below collarbone</li>
                <li><strong>24-28 inches:</strong> Opera length, sits at breastbone</li>
            </ul>
            
            <h3>Tips for Accurate Measurement</h3>
            <ul>
                <li>Measure at the end of the day when fingers are largest</li>
                <li>Consider the width of the ring band</li>
                <li>Account for seasonal changes in finger size</li>
                <li>When in doubt, choose a slightly larger size</li>
            </ul>
        </div>';
    }

    private function getCareInstructionsContent(): string
    {
        return '<div class="prose max-w-none">
            <h2>Jewelry Care Instructions</h2>
            
            <h3>General Care Tips</h3>
            <ul>
                <li>Store jewelry in a clean, dry place</li>
                <li>Keep pieces separated to prevent scratching</li>
                <li>Remove jewelry before swimming, exercising, or cleaning</li>
                <li>Apply perfume and cosmetics before putting on jewelry</li>
                <li>Clean regularly with appropriate methods</li>
            </ul>
            
            <h3>Gold Jewelry Care</h3>
            <h4>Cleaning</h4>
            <ul>
                <li>Use warm soapy water and a soft brush</li>
                <li>Rinse thoroughly and dry with a soft cloth</li>
                <li>For stubborn dirt, use a jewelry cleaning solution</li>
            </ul>
            
            <h4>Storage</h4>
            <ul>
                <li>Store in individual pouches or compartments</li>
                <li>Keep away from other metals to prevent scratching</li>
                <li>Use anti-tarnish strips in storage areas</li>
            </ul>
            
            <h3>Diamond Jewelry Care</h3>
            <h4>Cleaning</h4>
            <ul>
                <li>Soak in warm water with mild detergent</li>
                <li>Use a soft toothbrush to clean around settings</li>
                <li>Rinse well and dry with a lint-free cloth</li>
            </ul>
            
            <h4>Professional Cleaning</h4>
            <p>Have diamond jewelry professionally cleaned every 6 months to maintain brilliance.</p>
            
            <h3>Pearl Jewelry Care</h3>
            <h4>Special Considerations</h4>
            <ul>
                <li>Pearls are delicate and require gentle care</li>
                <li>Wipe with a soft, damp cloth after wearing</li>
                <li>Never use ultrasonic cleaners or harsh chemicals</li>
                <li>Store separately from other jewelry</li>
            </ul>
            
            <h3>Silver Jewelry Care</h3>
            <h4>Preventing Tarnish</h4>
            <ul>
                <li>Store in airtight containers</li>
                <li>Use anti-tarnish strips or chalk</li>
                <li>Wear regularly to prevent tarnishing</li>
            </ul>
            
            <h4>Removing Tarnish</h4>
            <ul>
                <li>Use a silver polishing cloth</li>
                <li>Apply silver cleaner for heavy tarnish</li>
                <li>Rinse thoroughly and dry completely</li>
            </ul>
            
            <h3>What to Avoid</h3>
            <ul>
                <li>Harsh chemicals and cleaning products</li>
                <li>Ultrasonic cleaners for delicate stones</li>
                <li>Extreme temperatures</li>
                <li>Storing different metals together</li>
                <li>Wearing jewelry during physical activities</li>
            </ul>
            
            <h3>Professional Services</h3>
            <p>We recommend professional cleaning and inspection annually to maintain your jewelry\'s beauty and integrity. Contact us to schedule a professional cleaning service.</p>
        </div>';
    }

    private function getContactUsContent(): string
    {
        return '<div class="prose max-w-none">
            <h2>Contact Us</h2>
            <p>We\'d love to hear from you! Get in touch with us for any questions about our jewelry, orders, or services.</p>
            
            <h3>Store Information</h3>
            <div class="grid md:grid-cols-2 gap-8 not-prose">
                <div>
                    <h4 class="text-lg font-semibold mb-2">Address</h4>
                    <p class="text-gray-600">
                        Lunora Jewelry Store<br>
                        123 Main Street<br>
                        Gulberg, Lahore<br>
                        Punjab, Pakistan
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-2">Contact Details</h4>
                    <p class="text-gray-600">
                        <strong>Phone:</strong> +92 42 1234 5678<br>
                        <strong>Email:</strong> info@lunora.com<br>
                        <strong>WhatsApp:</strong> +92 300 1234567
                    </p>
                </div>
            </div>
            
            <h3>Store Hours</h3>
            <div class="not-prose">
                <p class="text-gray-600">
                    <strong>Monday - Saturday:</strong> 10:00 AM - 8:00 PM<br>
                    <strong>Sunday:</strong> 12:00 PM - 6:00 PM<br>
                    <strong>Public Holidays:</strong> Closed
                </p>
            </div>
            
            <h3>Customer Service</h3>
            <p>Our customer service team is available to help you with:</p>
            <ul>
                <li>Product information and recommendations</li>
                <li>Order status and tracking</li>
                <li>Returns and exchanges</li>
                <li>Size and fitting guidance</li>
                <li>Custom jewelry requests</li>
                <li>Care and maintenance advice</li>
            </ul>
            
            <h3>Online Support</h3>
            <p>For immediate assistance, you can also reach us through:</p>
            <ul>
                <li><strong>Live Chat:</strong> Available on our website during business hours</li>
                <li><strong>Email Support:</strong> support@lunora.com (Response within 24 hours)</li>
                <li><strong>WhatsApp:</strong> +92 300 1234567</li>
            </ul>
            
            <h3>Visit Our Store</h3>
            <p>We invite you to visit our showroom to see our complete collection in person. Our knowledgeable staff will be happy to assist you in finding the perfect piece of jewelry.</p>
            
            <p><strong>Appointment Recommended:</strong> For personalized consultation and custom jewelry design, please call ahead to schedule an appointment.</p>
        </div>';
    }
}