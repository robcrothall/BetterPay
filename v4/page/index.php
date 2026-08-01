<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
$pageTitle = 'Home';
include __DIR__ . '/../inc/header.php';
?>
  <div class="w3-row-padding w3-margin-top">
    <div class="w3-col m7">
      <div class="w3-card-4 w3-padding-large">
        <h1 class="w3-text-blue">BetterPay Services</h1>
        <p class="w3-large">BetterPay Services helps households, employers, and work seekers connect with confidence. We support registration, profile management, availability tracking, interviews, and payroll support in a secure online environment.</p>
        <p><a class="w3-button w3-blue" href="/v4/page/register.php">Register today</a> <a class="w3-button w3-border" href="/v4/page/login.php">Log on</a></p>
      </div>
    </div>
    <div class="w3-col m5">
      <div class="w3-card-4 w3-padding-large">
        <h3>What we offer</h3>
        <ul class="w3-ul w3-hoverable">
          <li>Free registration for domestic workers and support staff</li>
          <li>Profile creation for employers and job seekers</li>
          <li>Availability and interview workflows</li>
          <li>Payroll, invoices, and communication tools</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="w3-row-padding w3-margin-top">
    <div class="w3-col m4">
      <div class="w3-card-4 w3-padding">
        <h3>For employers</h3>
        <p>Browse verified profiles, review qualifications, select candidates, and manage interviews with ease.</p>
      </div>
    </div>
    <div class="w3-col m4">
      <div class="w3-card-4 w3-padding">
        <h3>For work seekers</h3>
        <p>Create a professional profile, publish availability, upload supporting documents, and receive opportunities.</p>
      </div>
    </div>
    <div class="w3-col m4">
      <div class="w3-card-4 w3-padding">
        <h3>For administrators</h3>
        <p>Manage registrations, oversee approvals, and maintain communications and payroll activities securely.</p>
      </div>
    </div>
  </div>
<?php include __DIR__ . '/../inc/footer.php'; ?>
