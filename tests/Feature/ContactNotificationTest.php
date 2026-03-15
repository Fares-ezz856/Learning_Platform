<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Instructor;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ContactNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_send_message_to_instructor_and_trigger_notification()
    {
        // Mock FCM Server Key
        config(['services.firebase.server_key' => 'test-server-key']);
        putenv('FIREBASE_SERVER_KEY=test-server-key');

        $instructor = Instructor::create([
            'name' => 'Dr. Smith',
            'email' => 'smith@example.com',
            'password' => bcrypt('password'),
            'fcm_token' => 'test-fcm-token'
        ]);

        $student = Student::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password')
        ]);

        // Mock HTTP for FCM
        Http::fake([
            'fcm.googleapis.com/*' => Http::response(['message_id' => '123'], 200),
        ]);

        $response = $this->actingAs($student, 'student_web')
            ->post(route('contact'), [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'message' => 'Hello Instructor',
                'instructor_id' => $instructor->id
            ]);

        $response->assertStatus(302);
        
        $this->assertDatabaseHas('contacts', [
            'message' => 'Hello Instructor',
            'instructor_id' => $instructor->id
        ]);

        Http::assertSent(function ($request) {
            return $request->url() == 'https://fcm.googleapis.com/fcm/send' &&
                   $request['to'] == 'test-fcm-token';
        });
    }
}
