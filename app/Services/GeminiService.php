namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GeminiService
{
    protected $client;

    public function __construct()
    {
        // Initialize the Guzzle HTTP client
        $this->client = new Client([
            'base_uri' => "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}", // Replace with the actual base URI of the Gemini API
            'headers' => [
                'Authorization' => 'Bearer ' . env('GEMINI_API_KEY'),
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function getResponseInHtml($prompt)
    {
        try {
            $response = $this->client->post('generate', [ // Replace 'generate' with the correct endpoint
                'json' => [
                    'prompt' => $prompt,
                    'format' => 'html',  // Request the response in HTML format
                ],
            ]);

            // Decode the JSON response
            $data = json_decode($response->getBody(), true);

            // Return the HTML part of the response
            return $data['output']['html'] ?? null; // Adjust the key based on the actual API response format
        } catch (RequestException $e) {
            // Handle exceptions appropriately
            throw new \Exception('Failed to fetch response from Gemini API: ' . $e->getMessage());
        }
    }
}
