<?php

use Illuminate\Support\Facades\Route;
use SlimKit\PlusQuestion\API2\Controllers as API2;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'api/v2'], function (RouteRegisterContract $api) {

    // Question topics.
    // @Route /api/v2/question-topics
    $api->group(['prefix' => 'question-topics'], function (RouteRegisterContract $api) {

        // Question topics
        // @Get /api/v2/quest-topics
        $api->get('/', API2\TopicController::class.'@index');

        // Get a single topic.
        // @GET /api/v2/question-topics/:topic
        $api->get('/{topic}', API2\TopicController::class.'@show');

        // Get all experts for the topics.
        // @GET /api/v2/question-topics/:topic/experts
        $api->get('/{topic}/experts', API2\TopicExpertController::class.'@index');

        // List all question for topic.
        $api->get('/{topic}/questions', API2\TopicQuestionController::class.'@index');
    });

    // Questions.
    // @Route /api/v2/questions
    $api->group(['prefix' => 'questions'], function (RouteRegisterContract $api) {

        // List all questions.
        // @GET /api/v2/questions
        $api->get('/', API2\QuestionController::class.'@index');

        // Get a single question.
        // @GET /api/v2/questions/:question
        $api->get('/{question}', API2\QuestionController::class.'@show');

        // Answers.
        // @Route /api/v2/questions/:question/answers
        $api->group(['prefix' => '/{question}/answers'], function (RouteRegisterContract $api) {

            // Get all answers for question.
            // @GET /api/v2/questions/:question/answers
            $api->get('/', API2\AnswerController::class.'@index');
        });
    });

    // Answers.
    // @Route /api/v2/question-answers
    $api->group(['prefix' => 'question-answers'], function (RouteRegisterContract $api) {

        // Get a signle answer.
        // @GET /api/v2/question-answers/:answer
        $api->get('/{answer}', API2\AnswerController::class.'@show');

        // Get all answer rewarders.
        // @GET /api/v2/question-answers/:answer/rewarders
        $api->get('/{answer}/rewarders', API2\AnswerRewardController::class.'@index');

        // Get a list of users who like an answer.
        // @GET /api/v2/question-answers/:answer/likes
        $api->get('/{answer}/likes', API2\AnswerLikeController::class.'@index');
    });

    // question comments
    $api->get('/questions/{question}/comments', API2\CommentController::class.'@questionComments');

    // answer comments
    $api->get('/answers/{answer}/comments', API2\CommentController::class.'@answerComments');

    // @Auth api.
    // @Route /api/v2
    $api->group(['middleware' => 'auth:api'], function (RouteRegisterContract $api) {

        // User
        // @Route /api/v2/user
        $api->group(['prefix' => 'user'], function (RouteRegisterContract $api) {

            // Starred question topics.
            // @Route /api/v2/user/question-topics
            $api->group(['prefix' => 'question-topics'], function (RouteRegisterContract $api) {

                // Get follow question topics of the authenticated user.
                // @Get /api/v2/user/question-topics
                $api->get('/', API2\TopicUserController::class.'@index');

                // Follow a question topics.
                // @Put /api/v2/user/question-topics/:topic
                $api->put('/{topic}', API2\TopicUserController::class.'@store');

                // Unfollow a question topics.
                // @DELETE /api/v2/user/question-topics/:topic
                $api->delete('/{topic}', API2\TopicUserController::class.'@destroy');
            });

            // Watched questions.
            // @Route /api/v2/user/question-watches
            $api->group(['prefix' => 'question-watches'], function (RouteRegisterContract $api) {

                // List watched questions for the authenticated user.
                // @GET /api/v2/user/question-watches
                $api->get('/', API2\UserQuestionController::class.'@index');

                // Watch a question.
                // @PUT /api/v2/user/question-watches/:question
                $api->put('/{question}', API2\UserQuestionController::class.'@store');

                // Unwatch a question.
                // @DELETE /api/v2/user/question-watches/:question
                $api->delete('{question}', API2\UserQuestionController::class.'@destroy');
            });
        });

        // Question.
        // @Route /api/v2/questions
        $api->group(['prefix' => 'questions'], function (RouteRegisterContract $api) {

            // Publish a question.
            // @Post /api/v2/questions
            $api->post('/', API2\QuestionController::class.'@store');

            // Update a question.
            // $Patch /api/v2/questions/:question
            $api->patch('/{question}', API2\QuestionController::class.'@update');

            // Answer.
            // @Route /api/v2/question/:question/answers
            $api->group(['prefix' => '{question}/answers'], function (RouteRegisterContract $api) {

                // Send a answer for the question.
                // @Post /api/v2/questions/:question/answers
                $api->post('/', API2\AnswerController::class.'@store');
            });
        });

        // Question answers.
        // @Route /api/v2/question-answers
        $api->group(['prefix' => 'question-answers'], function (RouteRegisterContract $api) {

            // Give a reward.
            // @POST /api/v2/question-answers/:answer/rewarders
            $api->post('/{answer}/rewarders', API2\AnswerRewardController::class.'@store');

            // Like an answer.
            // @POST /api/v2/question-answers/:answer/likes
            $api->post('/{answer}/likes', API2\AnswerLikeController::class.'@store');

            // Cancel like an answer.
            // @DELETE /api/v2/question-answers/:answer/likes
            $api->delete('/{answer}/likes', API2\AnswerLikeController::class.'@destroy');
        });

        // 评论问题
        $api->post('/questions/{question}/comments', API2\CommentController::class.'@storeQuestionComment');

        // 评论回答
        $api->post('/questions/{question}/answers/{answer}', API2\CommentController::class.'@storeAnswerComment');
    });
});
