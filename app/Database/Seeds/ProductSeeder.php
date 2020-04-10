<?php 

namespace App\Database\Seeds;
use CodeIgniter\I18n\Time;

class ProductSeeder extends BaseSeeder
{

    public function run()
    {
        /**
         * Mzara entity seed
         */
        $seeder = \Config\Database::seeder();
        $seeder->call('NormalUserSeeder');

        $user_list = $this->db->table('user')->get()->getResult('array');
        helper('string');

        $image_seeder = new ImageSeeder($this->config, null);
        
        /**
         * Seed product_category
         */
        $i_end = $this->randomNumber(5, 20);
        for ($i=0; $i < $i_end; $i++) {
            $this->db->table('product_category')->insert(array(
                'designation' => $this->generateString(true)
            ));
        }

        $product_category_list = $this->db->table('product_category')->get()->getResult('array');

        $i_end = $this->randomNumber(50, 100);
        for ($i=0; $i < $i_end; $i++) {

            $user = $this->pickOne($user_list);
            $product_category = $this->pickOne($product_category_list);
            $name = $this->generateString(true);

            //seed image
            $gallery_id = $image_seeder->get_gallery();
            for ($x=0; $x < $this->randomNumber(1, 6); $x++) { 
                $image_id = $image_seeder->get_random_image($gallery_id, 'product', 'medecine');
            }

            //setting unique tag_name
            $tag_link = get_tag_view($name);
            $index = 0;

            do {
                $str = $tag_link;
                if ($index != 0) {
                    $str = $tag_link . '.' . $index;
                }

                $_data = $this->db->table('product')->where('tag_link', $str)->get()->getResult('array');
                $index++;

            }while($_data != null);

            if ($index != 0) {
                $tag_link = $tag_link . '.' . $index;
            }

            // seed product
            $this->db->table('product')->insert(array(
                'created_at' => Time::now()->toDateTimeString(),
                'user_id' => $user['id'],
                'name' => $name,
                'tag_link' => $tag_link,
                'description' => $this->generateString(),
                'price' => $this->randomNumber(1000, 1000000),
                'price_before' => $this->randomNumber(0, 1000000),
                'is_visible' => 1,
                'gallery_id' => $gallery_id,
                'product_category_id' => $product_category['id'],
            ));
        }

        /**
         * Seed command
         */
        $product_list = $this->db->table('product')->get()->getResult('array');

        $i_end = $this->randomNumber(20, 100);
        for ($i=0; $i < $i_end; $i++) {

            $user = $this->pickOne($user_list);
            
            $name = $this->generateString(true);
            $email = get_tag_view($this->generateString(true)) . '@email.com';

            // seed product
            $this->db->table('command')->insert(array(
                'created_at' => Time::now()->toDateTimeString(),
                'user_id' => $user['id'],
                'name' => $name,
                'email' => $email,
                'phone_number' => $this->randomNumber(1000, 1000000),
                'longitude' => $this->randomLatitude(),
                'longitude' => $this->randomLongitude(),
                'state_id' => $this->randomNumber(1, 5),
            ));

            $last = $this->db->table('command')
                                ->orderBy('id', 'desc')
                                ->get(1, 0)
                                ->getResult('array');

            $command_id = $last[0]['id'];

            /**
             * Seed Command Line
             */
            
            $j_end = $this->randomNumber(5, 15);
            for ($j=0; $j < $j_end; $j++) {
                $product = $this->pickOne($product_list);
            
                $name = $this->generateString(true);

                $this->db->table('command_line')->insert(array(
                    'command_id' => $command_id,
                    'product_id' => $product['id'],
                    'qte' => $this->randomNumber(1, 100),
                ));
            }
        }
    }
}