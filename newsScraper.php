<?php

// require __DIR__.'/example-app/bootstrap/autoload.php';
$app = require_once __DIR__.'/example-app/bootstrap/app.php';

// Initialize Laravel's database connection
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
  
// Import the Goutte client class  
use Goutte\Client;
use App\Models\Data;



$client = new Client();
    $url = "https://news.ycombinator.com/";  
    try{
        $crawler = $client->request('GET', $url);
    }catch(\Exception $e)
    {
        echo $e->getMessage();
        die();
    }
    
    $data = [];   
    $crawler->filter('.athing')->each(function ($node) use (&$data) {  
    
        $id = $node->filter('tr')->attr('id');  

        $title = $node->filter('.titleline')->text();  
    
        $link = $node->filter('.titleline > a')->attr('href');
    
        $data[$id]['title'] = $title; 
        $data[$id]['link'] = $link;  

    });

    $crawler->filter('.subline')->each(function ($node) use (&$data) {  
    
        $id = trim($node->filter('.score')->attr('id'), 'score_');  
        
        $score = trim($node->filter('.score')->text(), ' points');  
    
        $date = $node->filter('.age')->attr('title');
    
        $data[$id]['score'] = $score;
        $data[$id]['date'] = $date;  

    });

    foreach ($data as $item_id => $post) {
        // Create a new Post instance
        $newPost = new Data();

        // Assign attributes from the array to the Post instance
        $newPost->item_id = $item_id;
        $newPost->title = isset($post['title']) ? $post['title'] : null;
        $newPost->link = isset($post['link']) ? $post['link'] : null;
        $newPost->score = isset($post['score']) ? $post['score'] : null;
        $newPost->date = isset($post['date']) ? $post['date'] : null;

        // Save the Post instance to the database
        $newPost->save();
    }