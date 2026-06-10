<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Theme;

class NewThemesSeeder extends Seeder
{
    /**
     * Insert the ShopFront (ecommerce) and Corpora (business) themes.
     * Safe to run multiple times — uses updateOrCreate on slug.
     */
    public function run(): void
    {
        $themes = [
            [
                'slug'                => 'ecommerce',
                'name'                => 'ShopFront',
                'category'            => 'E-Commerce & Online Store',
                'description'         => 'A complete, conversion-optimised online store. Product categories, featured items, shop, cart, testimonials, and customer support — everything an e-commerce business needs.',
                'tags'                => ['E-Commerce', 'Shop', 'Products', 'Conversion'],
                'accent_color'        => '#f97316',
                'bg_color'            => '#ffffff',
                'preview_css'         => 'bg:#fff;text:#1a1a1a;accent:#f97316;card:#fff7ed',
                'hero_title'          => 'Premium Online Store',
                'hero_sub'            => 'Fashion · Electronics · Home · Beauty',
                'sections'            => ['Hero', 'Categories', 'Featured Products', 'About', 'Testimonials', 'Contact'],
                'best_for'            => 'Online Stores, D2C Brands, Retailers, E-Commerce Entrepreneurs',
                'profession_key'      => 'ecommerce',
                'profession_keywords' => ['ecommerce', 'e-commerce', 'online store', 'shop', 'retail', 'd2c'],
                'is_premium'          => true,
                'is_active'           => true,
                'sort_order'          => 7,
            ],
            [
                'slug'                => 'business',
                'name'                => 'Corpora',
                'category'            => 'Business & Company',
                'description'         => 'A complete corporate website for companies, real estate firms, travel agencies, and any business. Features services, divisions, team, case studies, and a strong contact section.',
                'tags'                => ['Corporate', 'Company', 'Real Estate', 'Travel'],
                'accent_color'        => '#0ea5e9',
                'bg_color'            => '#f8fafc',
                'preview_css'         => 'bg:#f8fafc;text:#0f172a;accent:#0ea5e9;card:#fff',
                'hero_title'          => 'Your Trusted Business Partner',
                'hero_sub'            => 'Real Estate · Travel · Consulting · Services',
                'sections'            => ['Hero', 'Services', 'About', 'Divisions', 'Testimonials', 'Contact'],
                'best_for'            => 'Companies, Real Estate Firms, Travel Agencies, Corporates',
                'profession_key'      => 'business',
                'profession_keywords' => ['business', 'company', 'corporate', 'real estate', 'travel', 'organisation', 'organization'],
                'is_premium'          => true,
                'is_active'           => true,
                'sort_order'          => 8,
            ],
        ];

        foreach ($themes as $data) {
            Theme::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('ShopFront (ecommerce) and Corpora (business) themes inserted/updated.');
    }
}
