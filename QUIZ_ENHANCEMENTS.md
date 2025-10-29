# Quiz System Enhancements

## Overview
The quiz system has been significantly enhanced with advanced features for a comprehensive learning management experience.

## New Database Fields

### Quizzes Table
- `allow_multiple_attempts` - Enable/disable multiple attempts
- `max_attempts` - Limit maximum number of attempts
- `shuffle_questions` - Randomize question order
- `shuffle_answers` - Randomize answer options
- `show_correct_answers` - Show correct answers after submission
- `show_results_immediately` - Display results immediately after quiz
- `questions_per_page` - Number of questions per page
- `allow_navigation` - Allow back/forward navigation
- `negative_marking` - Enable negative marking for wrong answers
- `negative_mark_value` - Fraction for negative marks (e.g., 0.25 = 25%)
- `status` - Draft, Published, or Archived
- `start_date` - Quiz availability start date
- `end_date` - Quiz availability end date
- `random_question_count` - Show random subset of questions
- `require_all_questions` - Force answering all questions
- `instructions` - Custom instructions for students
- `pass_message` - Custom message when student passes
- `fail_message` - Custom message when student fails

### Quiz Questions Table
- `explanation` - Explanation for the correct answer
- `difficulty` - Question difficulty level (easy, medium, hard)
- `tags` - JSON array of tags for categorization
- `partial_points` - Partial point allocation

### Quiz Attempts Table
- `attempt_number` - Attempt sequence number
- `total_points` - Total possible points
- `correct_answers` - Number of correct answers
- `incorrect_answers` - Number of incorrect answers
- `unanswered_questions` - Number of unanswered questions
- `time_spent` - JSON array tracking time per question
- `answers_review` - JSON array with detailed answer review
- `submitted` - Whether the attempt has been submitted

## New Models

### Quiz Model Enhancements
Added methods:
- `canUserAttempt($userId)` - Check if user can attempt quiz
- `getUserAttemptNumber($userId)` - Get next attempt number
- `isAvailable()` - Check if quiz is currently available
- `getQuestionsForAttempt()` - Get questions based on settings
- `getTotalPointsAttribute()` - Calculate total points
- `getUserBestScore($userId)` - Get user's best score
- `getAverageScoreAttribute()` - Calculate average score
- `getCompletionRateAttribute()` - Calculate completion rate
- `getPassRateAttribute()` - Calculate pass rate

### QuizQuestion Model Enhancements
- Added fields: explanation, difficulty, tags, partial_points
- Method: `getAnswersForAttempt()` - Get answers with shuffling

### QuizAttempt Model Enhancements
Added methods:
- `markAsPassed()` - Automatically determine pass status
- `calculateCompletionPercentage()` - Calculate completion %
- `getDurationAttribute()` - Get attempt duration in seconds
- `getFormattedDurationAttribute()` - Get formatted duration
- `isInProgress()` - Check if attempt is in progress

## New Controllers

### QuizQuestionController
Manages quiz questions and answers:
- `index()` - View all questions for a quiz
- `create()` - Create new question form
- `store()` - Save new question with answers
- `edit()` - Edit question form
- `update()` - Update question and answers
- `destroy()` - Delete question
- `reorder()` - Reorder questions

### QuizTakingController
Manages quiz taking experience:
- `start()` - Start a new quiz attempt
- `show()` - Display quiz taking interface
- `saveAnswer()` - Save user answers (AJAX)
- `submit()` - Submit completed quiz
- `result()` - Show quiz results
- `progress()` - Get progress status (AJAX)
- `calculateScore()` - Calculate score with negative marking

### QuizController Updates
- Enhanced `store()` and `update()` methods to handle all new fields

## New Routes

```
// Quiz taking
GET     /quizzes/{quiz}/start
GET     /quizzes/{quiz}/attempts/{attempt}
POST    /quizzes/{quiz}/attempts/{attempt}/save
POST    /quizzes/{quiz}/attempts/{attempt}/submit
GET     /quizzes/{quiz}/attempts/{attempt}/result
GET     /quizzes/{quiz}/attempts/{attempt}/progress

// Question management
GET     /quizzes/{quiz}/questions
GET     /quizzes/{quiz}/questions/create
POST    /quizzes/{quiz}/questions
GET     /quizzes/{quiz}/questions/{question}/edit
PUT     /quizzes/{quiz}/questions/{question}
DELETE  /quizzes/{quiz}/questions/{question}
POST    /quizzes/{quiz}/questions/reorder
```

## Updated Views

### Quiz Edit Form
- Comprehensive form with all advanced settings
- Organized into sections:
  - Basic Information
  - Assessment Settings
  - Attempts Settings
  - Display Settings
  - Results Settings
  - Negative Marking
  - Custom Messages
- JavaScript for dynamic field visibility

### Quiz Create Form
- Simplified creation form
- Auto-sets sensible defaults
- Can be enhanced in edit form later

### Quiz Show View
- Added "Manage Questions" button
- Shows quiz statistics and attempts

## Features Summary

### 1. Multiple Attempt Management
- Control whether students can retake quizzes
- Set maximum attempt limits
- Track attempt numbers
- Preserve best scores

### 2. Question & Answer Shuffling
- Randomize question order to prevent copying
- Shuffle answer options for better assessment
- Maintain data integrity

### 3. Flexible Scoring
- Standard scoring
- Negative marking for wrong answers
- Partial points support
- Custom scoring formulas per question

### 4. Advanced Display Options
- Questions per page pagination
- Navigation controls
- Progress tracking
- Real-time answer saving

### 5. Scheduling & Availability
- Start/end dates
- Time windows
- Draft/Published/Archived status
- Access control

### 6. Results & Feedback
- Immediate or delayed results
- Show/hide correct answers
- Custom pass/fail messages
- Detailed score breakdown

### 7. Random Question Selection
- Random subset of questions
- Maintains pool of questions
- Fresh experience per attempt

### 8. Analytics & Tracking
- Time spent per question
- Answer review tracking
- Completion rates
- Pass rates
- Average scores
- User performance history

## Usage

### Creating a Quiz
1. Go to lesson page
2. Click "Create Quiz"
3. Fill in basic information
4. Set default status (draft/published)
5. Save and edit for advanced settings

### Adding Questions
1. Click "Manage Questions" on quiz
2. Add questions with multiple answers
3. Mark correct answers
4. Add explanations
5. Set difficulty and tags

### Taking a Quiz
1. Find published quiz
2. Click "Start Quiz"
3. Answer questions (auto-saved)
4. Submit when ready
5. View results (if enabled)

## Next Steps

1. Create question management views in `resources/views/quizzes/questions/`
2. Create quiz taking views in `resources/views/quizzes/taking/`
3. Add JavaScript for auto-save and timer
4. Implement real-time progress tracking
5. Add question import/export features
6. Create analytics dashboard

## Migration Notes

Run the following migration:
```bash
php artisan migrate
```

This will add all new fields to existing tables without losing data.

## Model Relationships

```
Quiz
├── hasMany QuizQuestion
│   ├── hasMany QuizAnswer
│   └── hasMany UserAnswer
├── hasMany QuizAttempt
│   └── hasMany UserAnswer
└── belongsTo Lesson
    └── belongsTo Module
        └── belongsTo Course

QuizAttempt
├── belongsTo Quiz
├── belongsTo User
└── hasMany UserAnswer
    ├── belongsTo QuizQuestion
    └── belongsTo QuizAnswer
```

## Performance Considerations

- Questions are loaded with eager loading
- Answers are cached per attempt
- JSON fields for time tracking (flexible, not searchable)
- Index on user_id + quiz_id for quick lookup
- Batch updates for reordering

## Security Features

- User can only access their own attempts
- Quiz availability validation
- Attempt limit enforcement
- Time limit enforcement
- Can't modify submitted attempts
- Authorization checks on all actions
