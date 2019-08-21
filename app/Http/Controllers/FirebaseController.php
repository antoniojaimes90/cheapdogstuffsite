<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Goutte;
use Goutte\Client;
class FirebaseController extends Controller
{
	public function index(){
        $this->delete();
        $this->chewy();
        $this->petco();
    }    
    public function product(){
		$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/firstyearpuppy-firebase-adminsdk-4ozq2-5bc53f7cfa.json');
		$firebase 		  = (new Factory)
                        ->withServiceAccount($serviceAccount)
                        ->withDatabaseUri('https://firstyearpuppy.firebaseio.com')
                        ->create();
        $database 		= $firebase->getDatabase();
        $reference = $database->getReference('product');
        $snapshot = $reference->getSnapshot();
        $value = $snapshot->getValue();
        $products = array();
        foreach ($value as $item){
            array_push($products, $item); 
        }
        return response()->json($products);
        
    }
    public function landing(){
        return view("landing");
    }
    private function delete(){
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/firstyearpuppy-firebase-adminsdk-4ozq2-5bc53f7cfa.json');
		$firebase 		  = (new Factory)
                        ->withServiceAccount($serviceAccount)
                        ->withDatabaseUri('https://firstyearpuppy.firebaseio.com')
                        ->create();
        $database 		= $firebase->getDatabase();
        $database->getReference('product')->remove();
    }
    private function chewy(){
        $categories = array(
            "Food" => "https://www.chewy.com/deals/food-8099",
            "Food" => "https://www.chewy.com/deals/treats-8100",
            "Toys" => "https://www.chewy.com/deals/toys-8101",
            "Clothing" => "https://www.chewy.com/deals/clothing-accessories-8103",
            "Supplies" => "https://www.chewy.com/deals/supplies-8104"
        );
        foreach ($categories as $key => $value){            
            $client = new Client();
            $client->setHeader('user-agent', "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36");
            $crawler = $client->request('GET', $value);                    
            $crawler->filter('.results-products .product-holder')->each(function ($node) use ($key) {
                $cat = $key;
                $name = $node->filter('.image-holder img')->attr('alt');
                $link = "https://www.chewy.com{$node->filter('a')->attr('href')}";
                $price = $node->filter('.price strong')->html();
                $category = 'Toys';
                $img = $node->filter('.image-holder img')->attr('src');                   
                if(substr($img, 0, 2) == "//") $img = "https:{$img}";
            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/firstyearpuppy-firebase-adminsdk-4ozq2-5bc53f7cfa.json');
            $firebase 		  = (new Factory)
                            ->withServiceAccount($serviceAccount)
                            ->withDatabaseUri('https://firstyearpuppy.firebaseio.com')
                            ->create();
            $database 		= $firebase->getDatabase();        
            $newPost 		  = $database
                                ->getReference('product')
                                ->push(['Name' => $name,'store' => 'Chewy', 'Price' => $price, 'Category' => $cat, 'Link' => $link, 'img' => $img]);
            echo"<pre>";
            print_r($database);            
                
          
              });
        };
    }
    private function petco(){
        $categories = array(
            "Toys" => "https://www.petco.com/shop/en/petcostore/category/dog/dog-toys",
            "Supplies" => "https://www.petco.com/shop/en/petcostore/category/dog/dog-electronic-fence-systems",
            "Supplies" => "https://www.petco.com/shop/en/petcostore/specialty/crate-and-crate-accessories",
            "Supplies" => "https://www.petco.com/shop/en/petcostore/category/dog/dog-beds-and-bedding"
        );
        foreach ($categories as $key => $value){
            $crawler = Goutte::request('GET', $value);                    
            $crawler->filter('.prod-tile')->each(function ($node) use ($key) {
        
                $name = $node->filter('.product-image a')->attr('title');
                $link = $node->filter('.product-image a')->attr('href');
                $price = $node->filter('.product-price-promo')->html();
                $category = 'Toys';
                $img = $node->filter('.product-image picture .img-responsive')->attr('src');                   
                if(substr($img, 0, 2) == "//") $img = "https:{$img}";
            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/firstyearpuppy-firebase-adminsdk-4ozq2-5bc53f7cfa.json');
            $firebase 		  = (new Factory)
                            ->withServiceAccount($serviceAccount)
                            ->withDatabaseUri('https://firstyearpuppy.firebaseio.com')
                            ->create();
            $database 		= $firebase->getDatabase();        
            $newPost 		  = $database
                                ->getReference('product')
                                ->push(['Name' => $name,'store' => 'Petco', 'Price' => $price, 'Category' => $key, 'Link' => $link, 'img' => $img]);
            echo"<pre>";
            print_r($database);            
                
          
              });
        }
    }
}
?>