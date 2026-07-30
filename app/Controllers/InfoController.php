<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;

class InfoController {

    public function about(): void {
        Response::render('info/about', [], 'About Us - Kenyans Decision Independent Opinion Platform');
    }

    public function privacy(): void {
        Response::render('info/privacy', [], 'Privacy & Security Policy - Kenyans Decision');
    }

    public function terms(): void {
        Response::render('info/terms', [], 'Terms of Service & Community Guidelines - Kenyans Decision');
    }

    public function cookies(): void {
        Response::render('info/cookies', [], 'Cookie Policy & Data Transparency - Kenyans Decision');
    }

    public function methodology(): void {
        Response::render('info/methodology', [], 'Polling Methodology & Security Framework - Kenyans Decision');
    }

    public function faq(): void {
        Response::render('info/faq', [], 'Frequently Asked Questions & Trust Center - Kenyans Decision');
    }

    public function contact(Request $request): void {
        $success = false;
        $error = null;

        if ($request->isPost()) {
            $name = trim((string)$request->getParam('name'));
            $email = trim((string)$request->getParam('email'));
            $subject = trim((string)$request->getParam('subject'));
            $message = trim((string)$request->getParam('message'));

            if (empty($name) || empty($email) || empty($message)) {
                $error = 'Please fill in all required fields (Name, Email, Message).';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please provide a valid email address.';
            } else {
                $success = true;
            }
        }

        Response::render('info/contact', [
            'success' => $success,
            'error' => $error
        ], 'Contact Us & Editorial Office - Kenyans Decision');
    }
}
