<?php

namespace App\Services;

/**
 * Sentiment Analysis Service
 * 
 * Provides sentiment analysis for text feedback using a lexicon-based approach.
 * Analyzes the additional_comments field from student feedback to determine
 * emotional sentiment (positive, negative, or neutral).
 */
class SentimentAnalysisService
{
    /**
     * Positive sentiment words
     */
    private $positiveWords = [
        'good', 'great', 'excellent', 'amazing', 'wonderful', 'fantastic',
        'helpful', 'friendly', 'professional', 'satisfied', 'happy',
        'pleased', 'thank', 'thanks', 'appreciate', 'love', 'like',
        'best', 'better', 'awesome', 'outstanding', 'superb', 'brilliant',
        'effective', 'efficient', 'smooth', 'easy', 'clear', 'organized',
        'supportive', 'caring', 'understanding', 'patient', 'kind',
        'recommend', 'impressive', 'positive', 'enjoyed', 'comfortable',
        'quick', 'fast', 'responsive', 'attentive', 'knowledgeable',
        'polite', 'respectful', 'welcoming', 'warm', 'nice', 'pleasant',
        'satisfied', 'grateful', 'thankful', 'fortunate', 'lucky',
        'beneficial', 'valuable', 'insightful', 'encouraging', 'motivating',
        'inspiring', 'reliable', 'trustworthy', 'honest', 'genuine',
        'compassionate', 'empathetic', 'thoughtful', 'considerate', 'generous',
        'talented', 'skilled', 'expert', 'experienced', 'capable',

        // Tagalog words
        'maganda', 'mahusay', 'napakahusay', 'kamangha-mangha', 'kahanga-hanga', 'fantastic',
        'nakakatulong', 'palakaibigan', 'propesyonal', 'nasiyahan', 'masaya',
        'natutuwa', 'salamat', 'maraming salamat', 'pinahahalagahan', 'mahal', 'gusto',
        'pinakamaganda', 'mas mabuti', 'astig', 'natatangi', 'napakahusay', 'matalino',
        'epektibo', 'mahusay', 'maayos', 'madali', 'malinaw', 'organisado',
        'sumusuporta', 'maalaga', 'maunawain', 'matiyaga', 'mabait',
        'inirerekomenda', 'kahanga-hanga', 'positibo', 'nag-enjoy', 'komportable',
        'mabilis', 'mabilis', 'responsive', 'maasikaso', 'marunong',
        'magalang', 'may respeto', 'malugod', 'mainit ang pagtanggap', 'maayos', 'kaaya-aya',
        'nasiyahan', 'mapagpasalamat', 'mapagpasalamat', 'maswerte', 'swerte',
        'kapaki-pakinabang', 'mahalaga', 'may insight', 'nakakaengganyo', 'nakaka-motivate',
        'nakaka-inspire', 'maaasahan', 'mapagkakatiwalaan', 'tapat', 'totoo',
        'mahabagin', 'may empatiya', 'maalalahanin', 'maalaga', 'mapagbigay',
        'may talento', 'may kasanayan', 'eksperto', 'may karanasan', 'may kakayahan',

        // Cebuano
        'maayo', 'nindot', 'gwapo', 'lipay', 'ganahan', 'nakatabang',
        'buotan', 'maalam', 'komportable', 'paspas', 'kasaligan',
        'matinud-anon', 'maalalahanon', 'malumo', 'abtik', 'okay kaayo'


    ];

    /**
     * Negative sentiment words
     */
    private $negativeWords = [
        'bad', 'poor', 'terrible', 'awful', 'horrible', 'worst',
        'unhelpful', 'rude', 'unprofessional', 'dissatisfied', 'unhappy',
        'disappointed', 'frustrated', 'angry', 'annoyed', 'upset',
        'hate', 'dislike', 'slow', 'difficult', 'confusing', 'unclear',
        'disorganized', 'unresponsive', 'impatient', 'unkind', 'cold',
        'not recommend', 'negative', 'uncomfortable', 'worried', 'concerned',
        'problem', 'issue', 'trouble', 'difficult', 'hard', 'complicated',
        'delay', 'late', 'wait', 'waiting', 'long', 'boring', 'tedious',
        'stressful', 'stress', 'anxious', 'nervous', 'overwhelmed',
        'ignore', 'ignored', 'neglect', 'neglected', 'disrespectful',
        'unfair', 'unjust', 'wrong', 'mistake', 'error', 'fail', 'failed',
        'waste', 'wasted', 'useless', 'pointless', 'hopeless',
        'incompetent', 'inadequate', 'insufficient', 'lacking', 'missing',
        'unreliable', 'untrustworthy', 'dishonest', 'fake', 'insincere',
        'arrogant', 'condescending', 'patronizing', 'superior', 'judgmental',
        'inconsiderate', 'selfish', 'greedy', 'biased', 'dreadful',
        
        // Tagalog words
        'pangit', 'mahina', 'napakasama', 'kakila-kilabot', 'nakakatakot', 'pinakamasama',
        'hindi nakakatulong', 'bastos', 'hindi propesyonal', 'hindi nasiyahan', 'malungkot',
        'nadismaya', 'frustrated', 'galit', 'naiinis', 'nabubwisit',
        'kinamumuhian', 'ayaw', 'mabagal', 'mahirap', 'nakakalito', 'hindi malinaw',
        'magulo', 'hindi responsive', 'walang pasensya', 'masungit', 'malamig',
        'hindi inirerekomenda', 'negatibo', 'hindi komportable', 'nag-aalala', 'may concern',
        'problema', 'isyu', 'abala', 'mahirap', 'komplikado',
        'delay', 'late', 'hintay', 'naghihintay', 'matagal', 'nakakainip', 'nakakabagot',
        'nakaka-stress', 'stress', 'balisa', 'kinakabahan', 'overwhelmed',
        'ini-ignore', 'binale-wala', 'napabayaan', 'walang respeto',
        'hindi patas', 'mali', 'pagkakamali', 'error', 'nabigo',
        'sayang', 'walang silbi', 'walang kwenta', 'walang pag-asa',
        'walang kakayahan', 'kulang', 'hindi sapat', 'may kulang',
        'hindi maaasahan', 'hindi mapagkakatiwalaan', 'hindi tapat', 'peke', 'hindi sincere',
        'mayabang', 'mapangmataas', 'mapanghusga',
        'walang konsiderasyon', 'makasarili', 'sakim', 'biased', 'nakakatakot',

        // Cebuano
        'bati', 'dili maayo', 'dili nindot', 'kasuko', 'lagot',
        'problema', 'lisod', 'hinay', 'dugay', 'libog',
        'walay klaro', 'kapoy', 'stress', 'walay ayo'
    ];

    /**
     * Intensifiers that amplify sentiment
     */
    private $intensifiers = [
        'very', 'really', 'extremely', 'absolutely', 'completely',
        'totally', 'utterly', 'highly', 'incredibly', 'exceptionally'
    ];

    /**
     * Negators that flip sentiment
     */
    private $negators = [
        'not', 'no', 'never', 'none', 'neither', 'nor', 'nothing',
        'nobody', 'nowhere', 'hardly', 'barely', 'scarcely'
    ];

    /**
     * Analyze sentiment of text
     * 
     * @param string $text Text to analyze
     * @return array Analysis result with score, label, and details
     */
    public function analyze(string $text): array
    {
        if (empty(trim($text))) {
            return [
                'score' => 0,
                'label' => 'neutral',
                'confidence' => 0,
                'positive_count' => 0,
                'negative_count' => 0,
                'details' => 'No text provided'
            ];
        }

        // Normalize text
        $text = strtolower(trim($text));
        $words = preg_split('/\s+/', $text);
        
        $positiveCount = 0;
        $negativeCount = 0;
        $intensifierMultiplier = 1;
        $isNegated = false;

        foreach ($words as $word) {
            // Remove punctuation
            $cleanWord = preg_replace('/[^\w]/', '', $word);

            // Check for negators
            if (in_array($cleanWord, $this->negators)) {
                $isNegated = true;
                continue;
            }

            // Check for intensifiers
            if (in_array($cleanWord, $this->intensifiers)) {
                $intensifierMultiplier = 1.5;
                continue;
            }

            // Check for positive words
            if (in_array($cleanWord, $this->positiveWords)) {
                $weight = $intensifierMultiplier;
                if ($isNegated) {
                    $negativeCount += $weight;
                } else {
                    $positiveCount += $weight;
                }
                // Reset modifiers
                $intensifierMultiplier = 1;
                $isNegated = false;
                continue;
            }

            // Check for negative words
            if (in_array($cleanWord, $this->negativeWords)) {
                $weight = $intensifierMultiplier;
                if ($isNegated) {
                    $positiveCount += $weight;
                } else {
                    $negativeCount += $weight;
                }
                // Reset modifiers
                $intensifierMultiplier = 1;
                $isNegated = false;
                continue;
            }

            // Reset modifiers for non-sentiment words
            $intensifierMultiplier = 1;
            $isNegated = false;
        }

        // Calculate sentiment score (-1 to 1)
        $totalSentimentWords = $positiveCount + $negativeCount;
        
        if ($totalSentimentWords === 0) {
            return [
                'score' => 0,
                'label' => 'neutral',
                'confidence' => 0,
                'positive_count' => 0,
                'negative_count' => 0,
                'details' => 'No sentiment words found'
            ];
        }

        $score = ($positiveCount - $negativeCount) / $totalSentimentWords;
        
        // Determine label
        if ($score > 0.2) {
            $label = 'positive';
        } elseif ($score < -0.2) {
            $label = 'negative';
        } else {
            $label = 'neutral';
        }

        // Calculate confidence based on ratio of sentiment words to total words
        $totalWords = count($words);
        $confidence = min(1, $totalSentimentWords / max(1, $totalWords) * 2);

        return [
            'score' => round($score, 3),
            'label' => $label,
            'confidence' => round($confidence, 3),
            'positive_count' => $positiveCount,
            'negative_count' => $negativeCount,
            'details' => "Found {$positiveCount} positive and {$negativeCount} negative sentiment words"
        ];
    }

    /**
     * Get sentiment score for database storage (-100 to 100)
     * 
     * @param string $text Text to analyze
     * @return int Score from -100 (very negative) to 100 (very positive)
     */
    public function getScoreForStorage(string $text): int
    {
        $analysis = $this->analyze($text);
        return (int) round($analysis['score'] * 100);
    }

    /**
     * Get sentiment label only
     * 
     * @param string $text Text to analyze
     * @return string 'positive', 'negative', or 'neutral'
     */
    public function getLabel(string $text): string
    {
        $analysis = $this->analyze($text);
        return $analysis['label'];
    }

    /**
     * Batch analyze multiple texts
     * 
     * @param array $texts Array of texts to analyze
     * @return array Array of analysis results
     */
    public function batchAnalyze(array $texts): array
    {
        $results = [];
        foreach ($texts as $index => $text) {
            $results[$index] = $this->analyze($text);
        }
        return $results;
    }

    /**
     * Get sentiment statistics for a collection of feedback
     * 
     * @param array $feedbacks Array of feedback records with additional_comments
     * @return array Statistics summary
     */
    public function getStatistics(array $feedbacks): array
    {
        $positive = 0;
        $negative = 0;
        $neutral = 0;
        $totalScore = 0;
        $scores = [];

        foreach ($feedbacks as $feedback) {
            $comment = $feedback['additional_comments'] ?? '';
            $analysis = $this->analyze($comment);
            
            $scores[] = $analysis['score'];
            $totalScore += $analysis['score'];

            switch ($analysis['label']) {
                case 'positive':
                    $positive++;
                    break;
                case 'negative':
                    $negative++;
                    break;
                case 'neutral':
                    $neutral++;
                    break;
            }
        }

        $total = count($feedbacks);
        
        return [
            'total' => $total,
            'positive' => $positive,
            'negative' => $negative,
            'neutral' => $neutral,
            'positive_percentage' => $total > 0 ? round(($positive / $total) * 100, 2) : 0,
            'negative_percentage' => $total > 0 ? round(($negative / $total) * 100, 2) : 0,
            'neutral_percentage' => $total > 0 ? round(($neutral / $total) * 100, 2) : 0,
            'average_score' => $total > 0 ? round($totalScore / $total, 3) : 0,
            'score_distribution' => [
                'min' => $total > 0 ? min($scores) : 0,
                'max' => $total > 0 ? max($scores) : 0,
                'median' => $total > 0 ? $this->calculateMedian($scores) : 0
            ]
        ];
    }

    /**
     * Calculate median of an array
     */
    private function calculateMedian(array $arr): float
    {
        sort($arr);
        $count = count($arr);
        $mid = floor($count / 2);
        
        if ($count % 2) {
            return $arr[$mid];
        } else {
            return ($arr[$mid - 1] + $arr[$mid]) / 2;
        }
    }
}
