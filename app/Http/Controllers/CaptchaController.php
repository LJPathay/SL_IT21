<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CaptchaController extends Controller
{
    /**
     * Generate a new CAPTCHA
     */
    public function generate()
    {
        // Generate a simple math CAPTCHA
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $operators = ['+', '-'];
        $operator = $operators[array_rand($operators)];
        
        if ($operator == '+') {
            $answer = $num1 + $num2;
        } else {
            $answer = $num1 - $num2;
        }

        $captchaId = Str::random(32);
        $question = "{$num1} {$operator} {$num2} = ?";
        
        // Store in cache for 5 minutes
        Cache::put("captcha:{$captchaId}", $answer, 300);

        return response()->json([
            'captcha_id' => $captchaId,
            'question' => $question,
        ]);
    }

    /**
     * Validate CAPTCHA answer
     */
    public function validate(Request $request)
    {
        $request->validate([
            'captcha_id' => 'required|string',
            'answer' => 'required|integer',
        ]);

        $captchaId = $request->input('captcha_id');
        $userAnswer = $request->input('answer');
        $correctAnswer = Cache::get("captcha:{$captchaId}");

        if ($correctAnswer === null) {
            return response()->json([
                'valid' => false,
                'message' => 'CAPTCHA expired or invalid',
            ], 400);
        }

        if ($userAnswer == $correctAnswer) {
            // Mark as used
            Cache::forget("captcha:{$captchaId}");
            return response()->json([
                'valid' => true,
                'message' => 'CAPTCHA validated successfully',
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Incorrect answer',
        ], 400);
    }
}
