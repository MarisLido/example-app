<?php

namespace App\Http\Controllers;

use Goutte\Client;
use App\Models\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScraperController extends Controller
{
    public function scraper()
    {   
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

        $this->storePostsToDatabase($data);

        return redirect()->route('scraper.view')->with('success', 'Data scraped successfully!');
    }


    public function update()
    {   
        $client = new Client();
        $url = "https://news.ycombinator.com/";  
        try{
            $crawler = $client->request('GET', $url);
        }catch(\Exception $e)
        {
            echo $e->getMessage();
            die();
        }

        $crawler->filter('.subline')->each(function ($node) use (&$data) {  
        
            $id = trim($node->filter('.score')->attr('id'), 'score_');  
            
            $existingPost = Data::where('item_id', $id)->first();

            if($existingPost){
                $score = trim($node->filter('.score')->text(), ' points');

                $existingPost->update([
                        'score' => $score,
                    ]);
            }
        });

        return redirect()->route('scraper.view')->with('success', 'Data scraped successfully!');
    }


    public function delete($id)
    {
        // Find the record by ID
        $post = Data::findOrFail($id);
        
        // Delete the record
        $post->delete();

        // Redirect back to the scraper view
        return redirect()->route('scraper.view')->with('success', 'Record deleted successfully!');
    }


    function storePostsToDatabase($data)
    {
        foreach ($data as $item_id => $post) {

            $existingPost = Data::where('item_id', $item_id)->first();
            if ($existingPost) {
                // Update existing post
                $existingPost->update([
                    'title' => $post['title'] ?? null,
                    'link' => $post['link'] ?? null,
                    'score' => $post['score'] ?? null,
                    'date' => $post['date'] ?? null,
                ]);
            }
            else{
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
        }
    }


    public function showData()
    {
        // Fetch data from the database using the Post model
        $data = Data::all();

        // Pass the fetched data to the view
        return view('scraper.view', compact('data'));
    }
}
