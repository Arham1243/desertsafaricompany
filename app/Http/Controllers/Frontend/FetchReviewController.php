<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FetchReviewController extends Controller
{
    public function fetchReview(Request $request, $type = null)
    {
        $type = $type ?? $request->input('type');

        try {
            switch ($type) {
                case 'trustpilot':
                    return $this->fetchTrustpilotReview();
                case 'tripadvisor':
                    return null;
                case 'google':
                    return null;
                default:
                    return response()->json(['error' => 'Invalid review type'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch reviews', 'details' => $e->getMessage()], 500);
        }
    }

    public function fetchTrustpilotReview()
    {
        $client = new Client;
        $url = 'https://www.trustpilot.com/review/happydesertsafari.com';

        try {
            $response = $client->get($url);
            $htmlContent = $response->getBody()->getContents();

            $dom = new \DOMDocument;
            libxml_use_internal_errors(true);
            $dom->loadHTML($htmlContent);
            libxml_clear_errors();

            $scripts = $dom->getElementsByTagName('script');

            // Initialize review data with default values
            $reviewData = collect([
                'totalReviews' => null,
                'averageRating' => null,
                'reviewers' => collect(),
            ]);

            foreach ($scripts as $script) {
                if ($script->hasAttribute('data-business-unit-json-ld') && $script->getAttribute('data-business-unit-json-ld') === 'true') {
                    // Decode the JSON content of the script
                    $jsonContent = json_decode($script->nodeValue, true);

                    // Ensure '@graph' key exists and is an array
                    if (isset($jsonContent['@graph']) && is_array($jsonContent['@graph'])) {
                        $graphCollection = collect($jsonContent['@graph']);

                        // Find LocalBusiness and extract ratings safely
                        $localBusiness = $graphCollection->firstWhere('@type', 'LocalBusiness');
                        if ($localBusiness && isset($localBusiness['aggregateRating'])) {
                            $reviewData->put('totalReviews', $localBusiness['aggregateRating']['reviewCount'] ?? null);
                            $reviewData->put('averageRating', $localBusiness['aggregateRating']['ratingValue'] ?? null);
                        }

                        // Filter and collect reviewers' names (limit to 4)
                        $graphCollection->filter(function ($item) {
                            return isset($item['@type']) && $item['@type'] === 'Review'
                                && in_array($item['reviewRating']['ratingValue'], ['4', '5']);
                        })->take(4)->each(function ($item) use ($reviewData) {
                            if (isset($item['author']['name'])) {
                                $authorName = $item['author']['name'];
                                $nameParts = explode(' ', $authorName);

                                // Get initials from first and last name
                                $firstInitial = strtoupper(substr($nameParts[0], 0, 1)); // First name initial
                                $lastInitial = count($nameParts) > 1 ? strtoupper(substr($nameParts[1], 0, 1)) : '';

                                // Use push() method to add initials to the collection
                                $reviewData['reviewers']->push($firstInitial.$lastInitial);
                            }
                        });
                    }
                }
            }

            // Handle cases where totalReviews or averageRating are not available
            if ($reviewData['totalReviews'] === null) {
                $reviewData->put('totalReviews', 'N/A'); // Set a default value if missing
            }

            if ($reviewData['averageRating'] === null) {
                $reviewData->put('averageRating', 'N/A'); // Set a default value if missing
            }

            // Return the collected data
            return response()->json([
                'totalReviews' => $reviewData['totalReviews'],
                'averageRating' => $reviewData['averageRating'],
                'reviewers' => $reviewData['reviewers'],
                'reviewers_type' => 'names',
            ]);
        } catch (\Exception $e) {
            // Return a more specific error message
            return response()->json([
                'error' => 'Unable to fetch reviews',
                'message' => 'An error occurred while fetching the reviews from Trustpilot.',
            ], 500);
        }
    }
}
