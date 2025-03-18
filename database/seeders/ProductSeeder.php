<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Royal Canin Adult Cat Food',
                'description' => 'Comida premium para gatos adultos. Royal Canin Adult Cat Food es una opción premium que satisface las necesidades nutricionales específicas de los gatos adultos, proporcionándoles una dieta equilibrada y deliciosa para mantener su salud óptima.',
                'price' => 25.99,
                'stock' => 50,
                'supplier_id' => 1,
            ],
            [
                'name' => 'Hill\'s Science Diet Puppy Food',
                'description' => 'Comida para cachorros rica en nutrientes para un crecimiento óptimo. Hill\'s Science Diet Puppy Food ofrece una fórmula rica en nutrientes, diseñada especialmente para cachorros, asegurando un crecimiento saludable y brindando los elementos esenciales para su desarrollo óptimo.',
                'price' => 32.50,
                'stock' => 30,
                'supplier_id' => 1,
            ],
            [
                'name' => 'KONG Classic Dog Toy',
                'description' => 'Juguete de goma resistente para perros, perfecto para masticar y jugar. El KONG Classic Dog Toy es un juguete de goma resistente para perros, ideal para actividades de masticación y juego, proporcionando horas de entretenimiento duradero y satisfaciendo los instintos naturales de tu mascota.',
                'price' => 12.99,
                'stock' => 75,
                'supplier_id' => 2,
            ],
            [
                'name' => 'Feliway Cat Pheromone Diffuser',
                'description' => 'Difusor calmante para gatos que reduce el estrés y comportamientos no deseados. Feliway Cat Pheromone Diffuser es un innovador difusor que ayuda a reducir el estrés en gatos, creando un ambiente calmante y mitigando comportamientos no deseados, mejorando así la convivencia con tu felino.',
                'price' => 38.75,
                'stock' => 20,
                'supplier_id' => 3,
            ],
            [
                'name' => 'Blue Buffalo Wilderness Dog Treats',
                'description' => 'Premios para perros sin granos con carne real para una recompensa deliciosa. Blue Buffalo Wilderness Dog Treats son premios sin granos, enriquecidos con carne real, ofreciendo una recompensa deliciosa y saludable para perros, complementando una dieta equilibrada y nutritiva.',
                'price' => 8.49,
                'stock' => 40,
                'supplier_id' => 1,
            ],
            [
                'name' => 'ScoopFree Ultra Self-Cleaning Litter Box',
                'description' => 'Arenero automático para gatos con función de autolimpieza. ScoopFree Ultra Self-Cleaning Litter Box es un arenero automático para gatos con función de autolimpieza, proporcionando comodidad y frescura constante para tu felino, además de facilitar la tarea de mantener su espacio limpio.',
                'price' => 129.99,
                'stock' => 15,
                'supplier_id' => 3,
            ],
            [
                'name' => 'Pedigree Dentastix Dental Dog Treats',
                'description' => 'Premios masticables para perros que promueven la salud dental y reducen la placa. Pedigree Dentastix Dental Dog Treats son premios masticables que promueven la salud dental en perros, reduciendo la placa y mejorando la higiene bucal, mientras brindan una experiencia deliciosa para tu mascota.',
                'price' => 11.25,
                'stock' => 60,
                'supplier_id' => 2,
            ],
            [
                'name' => 'TetraColor Tropical Fish Flakes',
                'description' => 'Escamas para peces tropicales que realzan los colores de los peces. TetraColor Tropical Fish Flakes son escamas diseñadas para realzar los colores vibrantes de los peces tropicales, ofreciendo una alimentación completa y balanceada para mantener la vitalidad y la belleza de tus peces.',
                'price' => 5.99,
                'stock' => 80,
                'supplier_id' => 3,
            ],
            [
                'name' => 'Nature\'s Miracle Stain and Odor Remover',
                'description' => 'Limpiador efectivo para manchas y olores de mascotas en diversas superficies. Nature\'s Miracle Stain and Odor Remover es un limpiador efectivo diseñado para eliminar manchas y olores de mascotas en diversas superficies, proporcionando una solución confiable para mantener tu hogar fresco y limpio.',
                'price' => 16.95,
                'stock' => 25,
                'supplier_id' => 3,
            ],
            [
                'name' => 'Comfort Zone Calming Collar for Dogs',
                'description' => 'Collar ajustable con feromonas calmantes para perros. Comfort Zone Calming Collar for Dogs es un collar ajustable impregnado con feromonas calmantes, ofreciendo un enfoque natural para reducir la ansiedad y el estrés en perros, mejorando su bienestar emocional.',
                'price' => 22.50,
                'stock' => 30,
                'supplier_id' => 2,
            ],
            [
                'name' => 'Iams ProActive Health Mature Adult Cat Food',
                'description' => 'Comida especializada para gatos adultos mayores. Iams ProActive Health Mature Adult Cat Food es una comida especializada para gatos adultos mayores, diseñada para satisfacer sus necesidades específicas de salud y nutrición en esta etapa de la vida.',
                'price' => 19.75,
                'stock' => 35,
                'supplier_id' => 1,
            ],
            [
                'name' => 'Frisco Dog Poop Bags',
                'description' => 'Bolsas biodegradables para la recogida fácil y ecológica de excrementos de perro. Frisco Dog Poop Bags son bolsas biodegradables diseñadas para la recogida fácil y ecológica de excrementos de perro, brindando una opción práctica y respetuosa con el medio ambiente para los dueños de mascotas.',
                'price' => 9.99,
                'stock' => 100,
                'supplier_id' => 2,
            ],
            [
                'name' => 'Hartz UltraGuard Flea and Tick Collar for Cats',
                'description' => 'Collar duradero para gatos que protege contra pulgas y garrapatas. Hartz UltraGuard Flea and Tick Collar for Cats es un collar duradero que protege a los gatos contra pulgas y garrapatas, proporcionando una solución efectiva para mantener a tu felino libre de parásitos no deseados.',
                'price' => 7.25,
                'stock' => 45,
                'supplier_id' => 2,
            ],
            [
                'name' => 'Nutro Wholesome Essentials Small Breed Dog Food',
                'description' => 'Comida nutritiva para perros de razas pequeñas. Nutro Wholesome Essentials Small Breed Dog Food es una comida nutritiva formulada específicamente para perros de razas pequeñas, asegurando una alimentación completa y equilibrada para satisfacer sus necesidades únicas.',
                'price' => 29.99,
                'stock' => 20,
                'supplier_id' => 3,
            ],
            [
                'name' => 'Zoo Med ReptiBreeze Open Air Screen Cage',
                'description' => 'Jaula resistente y bien ventilada para reptiles y anfibios. Zoo Med ReptiBreeze Open Air Screen Cage es una jaula resistente y bien ventilada, ideal para reptiles y anfibios, proporcionando un ambiente seguro y cómodo para tus mascotas de sangre fría.',
                'price' => 54.95,
                'stock' => 10,
                'supplier_id' => 1,
            ],
            [
                'name' => 'Wellness CORE Grain-Free Catnip Cat Treats',
                'description' => 'Golosinas irresistibles para gatos hechas con carne real y catnip. Wellness CORE Grain-Free Catnip Cat Treats son golosinas irresistibles para gatos, elaboradas con carne real y catnip, ofreciendo una recompensa deliciosa que complacerá el paladar de tu felino.',
                'price' => 6.79,
                'stock' => 50,
                'supplier_id' => 1,
            ],
            [
                'name' => 'KAYTEE Forti-Diet Pro Health Rabbit Food',
                'description' => 'Comida nutritiva para conejos que promueve la salud general. KAYTEE Forti-Diet Pro Health Rabbit Food es una comida nutritiva para conejos, formulada para promover la salud general y el bienestar de estos pequeños animales.',
                'price' => 14.50,
                'stock' => 25,
                'supplier_id' => 1,
            ],
            [
                'name' => 'Exo Terra Heat-Glo Infrared Spot Lamp',
                'description' => 'Lámpara de calor infrarroja para reptiles que crea un punto cálido para descansar. Exo Terra Heat-Glo Infrared Spot Lamp es una lámpara de calor infrarroja diseñada para reptiles, creando un punto cálido esencial para el descanso y la termorregulación de tus mascotas de sangre fría.',
                'price' => 18.99,
                'stock' => 15,
                'supplier_id' => 2,
            ],
            [
                'name' => 'Purina Pro Plan Savor Wet Cat Food Variety Pack',
                'description' => 'Paquete variado de comida húmeda para gatos con sabores deliciosos. Purina Pro Plan Savor Wet Cat Food Variety Pack es un paquete variado de comida húmeda para gatos, ofreciendo sabores deliciosos que satisfacen los gustos exigentes de tu felino.',
                'price' => 23.50,
                'stock' => 30,
                'supplier_id' => 1,
            ],
            [
                'name' => 'Greenies Original Dental Dog Chews',
                'description' => 'Chuches dentales para perros que promueven la salud bucal y un aliento fresco. Greenies Original Dental Dog Chews son chuches dentales para perros que no solo promueven la salud bucal, sino que también proporcionan un aliento fresco, siendo una opción deliciosa y beneficiosa para la higiene oral de tu mascota.',
                'price' => 21.25,
                'stock' => 40,
                'supplier_id' => 1,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
