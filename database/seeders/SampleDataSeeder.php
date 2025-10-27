<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample courses
        $course1 = Course::create([
            'title' => 'English for Beginners',
            'slug' => 'english-for-beginners',
            'description' => 'Learn basic English vocabulary, grammar, and conversation skills for beginners.',
            'level' => 'beginner',
            'price' => 0,
            'is_published' => true,
            'duration_hours' => 20,
        ]);

        $course2 = Course::create([
            'title' => 'Intermediate English Grammar',
            'slug' => 'intermediate-english-grammar',
            'description' => 'Master intermediate English grammar concepts and improve your writing skills.',
            'level' => 'intermediate',
            'price' => 150000,
            'is_published' => true,
            'duration_hours' => 30,
        ]);

        $course3 = Course::create([
            'title' => 'Advanced Business English',
            'slug' => 'advanced-business-english',
            'description' => 'Professional English for business communication, presentations, and negotiations.',
            'level' => 'advanced',
            'price' => 300000,
            'is_published' => true,
            'duration_hours' => 40,
        ]);

        // Create modules for course 1
        $module1_1 = Module::create([
            'course_id' => $course1->id,
            'title' => 'Basic Vocabulary',
            'description' => 'Learn essential English words and phrases',
            'order' => 1,
        ]);

        $module1_2 = Module::create([
            'course_id' => $course1->id,
            'title' => 'Simple Grammar',
            'description' => 'Introduction to basic English grammar',
            'order' => 2,
        ]);

        // Create lessons for module 1
        $lesson1_1_1 = Lesson::create([
            'module_id' => $module1_1->id,
            'title' => 'Greetings and Introductions',
            'description' => 'Learn how to greet people and introduce yourself',
            'type' => 'video',
            'content_url' => 'https://example.com/video1',
            'duration_minutes' => 15,
            'order' => 1,
            'is_free' => true,
        ]);

        $lesson1_1_2 = Lesson::create([
            'module_id' => $module1_1->id,
            'title' => 'Numbers and Colors',
            'description' => 'Learn numbers 1-100 and basic colors',
            'type' => 'reading',
            'content_text' => 'Numbers and colors are fundamental vocabulary in English...',
            'duration_minutes' => 20,
            'order' => 2,
            'is_free' => true,
        ]);

        // Create quiz for lesson 1
        $quiz1 = Quiz::create([
            'lesson_id' => $lesson1_1_1->id,
            'title' => 'Greetings Quiz',
            'description' => 'Test your knowledge of greetings and introductions',
            'passing_score' => 70,
            'time_limit_minutes' => 10,
        ]);

        // Create quiz questions
        $question1 = QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question' => 'What is the most common way to greet someone in English?',
            'type' => 'multiple_choice',
            'points' => 1,
            'order' => 1,
        ]);

        // Create quiz answers
        QuizAnswer::create([
            'quiz_question_id' => $question1->id,
            'answer_text' => 'Hello',
            'is_correct' => true,
        ]);

        QuizAnswer::create([
            'quiz_question_id' => $question1->id,
            'answer_text' => 'Goodbye',
            'is_correct' => false,
        ]);

        QuizAnswer::create([
            'quiz_question_id' => $question1->id,
            'answer_text' => 'Thank you',
            'is_correct' => false,
        ]);

        QuizAnswer::create([
            'quiz_question_id' => $question1->id,
            'answer_text' => 'Please',
            'is_correct' => false,
        ]);

        // Create modules for course 2
        $module2_1 = Module::create([
            'course_id' => $course2->id,
            'title' => 'Tenses',
            'description' => 'Learn about different verb tenses',
            'order' => 1,
        ]);

        $module2_2 = Module::create([
            'course_id' => $course2->id,
            'title' => 'Conditionals',
            'description' => 'Master conditional sentences',
            'order' => 2,
        ]);

        // Create lessons for module 2
        $lesson2_1_1 = Lesson::create([
            'module_id' => $module2_1->id,
            'title' => 'Present Simple Tense',
            'description' => 'Learn how to use present simple tense correctly',
            'type' => 'video',
            'content_url' => 'https://example.com/video2',
            'duration_minutes' => 25,
            'order' => 1,
            'is_free' => false,
        ]);

        // Create modules for course 3
        $module3_1 = Module::create([
            'course_id' => $course3->id,
            'title' => 'Business Communication',
            'description' => 'Professional communication skills',
            'order' => 1,
        ]);

        $module3_2 = Module::create([
            'course_id' => $course3->id,
            'title' => 'Presentations',
            'description' => 'How to give effective business presentations',
            'order' => 2,
        ]);

        // Create lessons for module 3
        $lesson3_1_1 = Lesson::create([
            'module_id' => $module3_1->id,
            'title' => 'Email Writing',
            'description' => 'Professional email writing techniques',
            'type' => 'reading',
            'content_text' => 'Professional email writing is crucial in business communication...',
            'duration_minutes' => 30,
            'order' => 1,
            'is_free' => false,
        ]);
    }
}
