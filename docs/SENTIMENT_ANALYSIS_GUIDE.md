# Sentiment Analysis Guide

## Overview

The Counselign system now includes sentiment analysis for student feedback. This feature analyzes the text comments students provide when submitting feedback to determine the emotional sentiment (positive, negative, or neutral).

## How It Works

The sentiment analysis uses a **lexicon-based approach** that:
- Analyzes text from the `additional_comments` field in student feedback
- Uses predefined word lists for positive and negative sentiment words
- Handles intensifiers (e.g., "very", "extremely") that amplify sentiment
- Handles negators (e.g., "not", "never") that flip sentiment
- Returns a sentiment score (-100 to 100) and label (positive/negative/neutral)

## Installation

### 1. Run the Database Migration

```bash
php spark migrate
```

This will add two new fields to the `student_feedback` table:
- `sentiment_score` (INT): Score from -100 (very negative) to 100 (very positive)
- `sentiment_label` (VARCHAR): Label: 'positive', 'negative', or 'neutral'

### 2. Files Created/Modified

**Created:**
- `app/Services/SentimentAnalysisService.php` - Core sentiment analysis logic
- `app/Database/Migrations/2026-04-27-120000_AddSentimentFieldsToFeedback.php` - Database migration

**Modified:**
- `app/Controllers/Student/Feedback.php` - Integrated sentiment analysis on feedback submission
- `app/Models/StudentFeedbackAnalyticsModel.php` - Added sentiment statistics methods

## Usage Examples

### 1. Basic Sentiment Analysis

```php
use App\Services\SentimentAnalysisService;

$sentimentService = new SentimentAnalysisService();

// Analyze a single text
$text = "The service was very helpful and I'm satisfied with the experience.";
$analysis = $sentimentService->analyze($text);

// Returns:
// [
//     'score' => 0.5,
//     'label' => 'positive',
//     'confidence' => 0.8,
//     'positive_count' => 3,
//     'negative_count' => 0,
//     'details' => 'Found 3 positive and 0 negative sentiment words'
// ]
```

### 2. Get Score for Database Storage

```php
$sentimentService = new SentimentAnalysisService();

$text = "I was not happy with the service.";
$score = $sentimentService->getScoreForStorage($text);

// Returns: -50 (integer from -100 to 100)
```

### 3. Get Label Only

```php
$sentimentService = new SentimentAnalysisService();

$text = "The experience was neutral, nothing special.";
$label = $sentimentService->getLabel($text);

// Returns: 'neutral'
```

### 4. Batch Analysis

```php
$sentimentService = new SentimentAnalysisService();

$texts = [
    "Great service!",
    "Terrible experience",
    "It was okay"
];

$results = $sentimentService->batchAnalyze($texts);

// Returns array of analysis results for each text
```

### 5. Get Statistics for Feedback Collection

```php
$sentimentService = new SentimentAnalysisService();

$feedbacks = [
    ['additional_comments' => 'Very helpful staff'],
    ['additional_comments' => 'Not satisfied with the wait time'],
    ['additional_comments' => 'Good experience overall']
];

$stats = $sentimentService->getStatistics($feedbacks);

// Returns:
// [
//     'total' => 3,
//     'positive' => 2,
//     'negative' => 1,
//     'neutral' => 0,
//     'positive_percentage' => 66.67,
//     'negative_percentage' => 33.33,
//     'neutral_percentage' => 0,
//     'average_score' => 0.33,
//     'score_distribution' => [...]
// ]
```

## Using the Analytics Model

### Get Sentiment Statistics

```php
use App\Models\StudentFeedbackAnalyticsModel;

$analyticsModel = new StudentFeedbackAnalyticsModel();

// Get overall sentiment statistics
$stats = $analyticsModel->getSentimentStatistics();

// Get statistics for specific counselor
$counselorStats = $analyticsModel->getSentimentStatistics([
    'counselor_id' => '2023307088'
]);

// Get statistics for date range
$dateRangeStats = $analyticsModel->getSentimentStatistics([
    'start_date' => '2026-01-01',
    'end_date' => '2026-12-31'
]);
```

### Get Sentiment Trend Over Time

```php
$analyticsModel = new StudentFeedbackAnalyticsModel();

// Get 12-month sentiment trend
$trend = $analyticsModel->getSentimentTrend(12);

// Returns array with monthly data:
// [
//     [
//         'month' => 'January 2026',
//         'average_score' => 25,
//         'positive_percentage' => 70,
//         'negative_percentage' => 15,
//         'neutral_percentage' => 15,
//         'total_feedbacks' => 20
//     ],
//     ...
// ]
```

### Get Negative Feedback for Review

```php
$analyticsModel = new StudentFeedbackAnalyticsModel();

// Get all negative feedback
$negativeFeedback = $analyticsModel->getNegativeFeedback();

// Get negative feedback for specific counselor
$counselorNegative = $analyticsModel->getNegativeFeedback([
    'counselor_id' => '2023307088'
]);

// Get negative feedback for date range
$dateRangeNegative = $analyticsModel->getNegativeFeedback([
    'start_date' => '2026-01-01',
    'end_date' => '2026-12-31'
]);
```

## Automatic Integration

The sentiment analysis is **automatically integrated** into the feedback submission process:

1. When a student submits feedback via `Feedback::submit()`
2. The `additional_comments` field is analyzed
3. The sentiment score and label are automatically stored in the database
4. No additional code needed for basic usage

## Sentiment Word Lists

The service includes predefined word lists that can be customized:

### Positive Words
good, great, excellent, amazing, wonderful, fantastic, helpful, friendly, professional, satisfied, happy, pleased, thank, thanks, appreciate, love, like, best, better, awesome, outstanding, superb, brilliant, effective, efficient, smooth, easy, clear, organized, supportive, caring, understanding, patient, kind, recommend, impressive, positive, enjoyed, comfortable, quick, fast, responsive, attentive, knowledgeable, polite, respectful, welcoming, warm, nice, pleasant, satisfied, grateful, thankful, fortunate, lucky

### Negative Words
bad, poor, terrible, awful, horrible, worst, unhelpful, rude, unprofessional, dissatisfied, unhappy, disappointed, frustrated, angry, annoyed, upset, hate, dislike, slow, difficult, confusing, unclear, disorganized, unresponsive, impatient, unkind, cold, not recommend, negative, uncomfortable, worried, concerned, problem, issue, trouble, difficult, hard, complicated, delay, late, wait, waiting, long, boring, tedious, stressful, stress, anxious, nervous, overwhelmed, ignore, ignored, neglect, neglected, disrespectful, unfair, unjust, wrong, mistake, error, fail, failed, waste, wasted, useless, pointless, hopeless

### Intensifiers
very, really, extremely, absolutely, completely, totally, utterly, highly, incredibly, exceptionally

### Negators
not, no, never, none, neither, nor, nothing, nobody, nowhere, hardly, barely, scarcely

## Customizing Word Lists

To customize the word lists, edit `app/Services/SentimentAnalysisService.php`:

```php
class SentimentAnalysisService
{
    private $positiveWords = [
        // Add your custom positive words here
        'awesome', 'fantastic', // ...
    ];

    private $negativeWords = [
        // Add your custom negative words here
        'terrible', 'awful', // ...
    ];
}
```

## API Response Format

### analyze() Method

```php
[
    'score' => float,           // -1.0 to 1.0
    'label' => string,          // 'positive', 'negative', or 'neutral'
    'confidence' => float,      // 0.0 to 1.0
    'positive_count' => int,    // Number of positive words found
    'negative_count' => int,    // Number of negative words found
    'details' => string         // Human-readable description
]
```

### getStatistics() Method

```php
[
    'total' => int,
    'positive' => int,
    'negative' => int,
    'neutral' => int,
    'positive_percentage' => float,
    'negative_percentage' => float,
    'neutral_percentage' => float,
    'average_score' => float,
    'score_distribution' => [
        'min' => float,
        'max' => float,
        'median' => float
    ]
]
```

## Use Cases

### 1. Monitor Counselor Performance
Track sentiment trends for individual counselors to identify training needs or recognize excellence.

### 2. Identify Issues Early
Use the `getNegativeFeedback()` method to review negative comments and address concerns proactively.

### 3. Track Overall Satisfaction
Monitor sentiment trends over time to measure the impact of system improvements.

### 4. Generate Reports
Create dashboards showing sentiment distribution and trends for administrators.

### 5. Automated Alerts
Set up alerts when negative sentiment exceeds a threshold (e.g., >30% negative feedback in a month).

## Limitations

- The lexicon-based approach is language-dependent (currently optimized for English)
- Sarcasm and context-dependent meaning may not be detected accurately
- The confidence score depends on the ratio of sentiment words to total words
- Very short comments may result in neutral sentiment due to insufficient data

## Future Enhancements

Potential improvements for the sentiment analysis system:

1. **Machine Learning Integration**: Replace lexicon-based approach with ML models for better accuracy
2. **Multi-language Support**: Add word lists for other languages
3. **Aspect-based Sentiment**: Analyze sentiment for specific aspects (e.g., staff, technology, wait time)
4. **Emotion Detection**: Detect specific emotions (anger, joy, sadness, etc.)
5. **Context-aware Analysis**: Handle sarcasm and complex sentence structures better

## Troubleshooting

### Migration Fails
If the migration fails, check:
- Database connection is working
- `student_feedback` table exists
- You have proper database permissions

### Sentiment Always Neutral
If sentiment analysis returns neutral for all feedback:
- Check if `additional_comments` field contains text
- Verify the word lists include relevant terms
- Consider adding custom words to the lists

### Performance Issues
If sentiment analysis is slow:
- Consider caching results for frequently accessed feedback
- Use batch analysis for processing multiple feedback records
- Add database indexes on sentiment_label field

## Support

For issues or questions about sentiment analysis:
1. Check this documentation
2. Review the code in `app/Services/SentimentAnalysisService.php`
3. Test with the examples provided above
4. Contact the development team for complex issues
