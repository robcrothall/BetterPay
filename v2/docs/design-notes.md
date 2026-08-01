# BetterPay design notes and prompt

## Goals

- Replace the existing static site (www.betterpay.co.za) with a PHP/MySQL application under the v2 folder.
- Present most of the information provided by the static site for users who have not registered and logged on.
- Support public browsing, registration, role-based profiles, availability management, and payroll workflows.
- Keep the implementation straightforward while remaining extensible.

## Development approach

The development should occur in phases. Each ends with a gate review resulting in revisions or approval to proceed to the next phase eg:
1. Database Design, referential integrity, indeces, review by me, negotiated changes, log in 
2. Simple home page with logo and menu, Registration, Logon/Logoff, Reset Password, Maintenance of user data, creation and maintenance of lookup tables eg titles, cities
3. Business employer profile, Personal employer profile, Maintenance of profile data
4. Uploading of documents and images and linking them to the profile, displaying the names (as a link to the content) of the documents on the profile, opening on a new tab if selected
5. Work seeker profile, availability of the work seeker
6. Payroll profile for employers
7. Timesheet data capture for employees, who must be among those who have registered on this website as work seekers.  Include a warning to adjust availability.
8. Export payroll data as a CSV file - format to be decided later
9. Add the information currently on the www.betterpay.co.za site to the Home page, probably in a single large index.php file
10. End of development, enter maintenance phase.

## Data model

The initial schema supports:

- user accounts and authentication
- password reset tokens
- business or personal profiles per user (one user can have several profiles):
  - Client (Employer)
  - Work seeker (Employee)
  - Administrator (can view and update any Client or Work seeker records on their behalf)
- Documents and images can be uploaded, described, and categorised by the user and associated with one of their profiles. Use an intersection table to link the document or image to the profile.
- availability records for work seekers (can have several eg night shift on Monday, Wednesday, and Friday, plus morning shift on Saturday and Sunday) providing for:
  - available from a date
  - available on specific days of the week on day (07h00 to 19h00) or night (19h00 to 07h00) 12-hour shifts, or morning (08h00 to 12h00) or afternoon (13h00 to 17h00) 
- audit logging for change diagnostics
- zero-to-many links from user to uploaded documents, with a document title (eg police clearance certificate, copy of ID document, CV, certificates of competence, etc) using an intersection table linking the profile to the uploaded document
- tables to support the current phase of the project.

Use normalised tables (level 2, at least).
Each table should have:
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
and
  created_by INT UNSIGNED DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_by INT UNSIGNED DEFAULT NULL,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
and
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


## Implementation notes

- The site uses W3.CSS for a simple responsive UI.
- Database credentials are stored outside the repository in inc/constants.php.
- Audit events are retained for 365 days and then purged automatically.
- Ignore the v1 directory

## Prompt

I would like to create a PHP and MySQL website in the current directory (OneDrive\Documents\Dev\BetterPay\v2) which should be set up as the development site.  This development site will be migrated to production after each GitHub Commit at www.betterpay.co.za in the v2 directory – there is a site in www.betterpay.co.za there currently, but the new site will replace it completely in https://betterpay.co.za/v2 .  Review the contents of www.betterpay.co.za .  It is currently a static site that needs to be replaced by the site you generate.
Please prepare instructions for installing PHP on this PC and linking it to IIS, which is currently installed and working.  Create the necessary files and instructions to get the development site working.
Create appropriate subfolders for the code in v2.  Prepare instructions for linking to the GitHub repository at https://github.com/robcrothall/BetterPay .  Create or replace a standard set of files such as README.md, .gitignore, and a change log, etc, in the development directory.  The .gitignore file should ensure that the inc/constants.php file is never migrated to GitHub.  Use a recommended open source license, and standard comments at the start of each PHP source file.
Review the code already in the “v2” directory, particularly in the “samples” and “inc” (include) subdirectories and consider the style of coding.  Improve on that style and the functionality of the modules in functions.php, and delete those modules which are not required.  Replace the .gitignore file with one that you generate – the file inc/constants.php should be ignored by Git and should not be uploaded to GitHub.
You will find the database name, username, and password in the file inc/constants.php . Generate a file “tables.sql” file to define how the tables should be created.  Enhance or improve the proposed design and specification eg more fields may be required on data entry screens.
Every row of every table should include fields to identify the user who created the row, datestamp when it was created, the last user who updated it, and a datestamp that reflects the date and time of the last update.  These fields in general will not be visible to users who update records via the PHP programs.
Whenever a field is updated or deleted in the database tables, log the change in an archive table that identifies the row that was changed, the previous value that has been deleted or changed, the new value, who changed it, with a timestamp.  The archived changes can be deleted after 365 days.  The purpose of this table is to diagnose issues and not for rollback.
I prefer the w3 css library but am comfortable if you need Bootstrap.
People should click on a “Logon” menu item and be presented with a screen that captures their username and password, with a link to register if they have not yet registered.
People will register on the site using:
•	their first_name, surname, and a given name (optional)
•	a username (defaulting to their email address or, if email is not available, their mobile phone number
•	ID type and ID number
•	email address
•	mobile phone number
•	landline number (optional)
•	password
We need provision for them to be able to change their password if they have forgotten it – we need to send a link to their email address which will allow them to change the password.
People who have registered can update their own personal details and can unsubscribe from email communications but will normally receive occasional email notifications of new announcements published on the website in Blog form.  A link to the blog document in the “docs” directory will be emailed to all users who have not opted out as soon as the blog file has been uploaded to the docs directory and published on the website.  Viewing of blogs is permitted for everyone who has a link to the document and registered users.
Once registered, they may create a profile as a potential employer (a Business and/or Personal Client), and/or as a work seeker.  The person can have zero or more of these roles.  Data required for each role are as follows:
Administrator:
Business Client – linked to person (use an intersection table):
•	Business name
•	Company Registration Number (optional)
•	Physical address
•	Company phone number
•	SARS Tax No for the business (optional)
•	Notes and documents and company logo provided for administrators (optional) – use an intersection table pointing to the profile and the document
•	A Client registration will initiate an invoice being sent to their email address and the suspension date of their profile will be set to five calendar days from the date of profile creation.  The suspension date will be extended by an administrator if payment for the invoice is received.
Personal Client:
•	SARS Tax No
•	Notes and documents and company logo provided for administrators (optional) – use an intersection table pointing to the profile and the document
•	A Client registration will initiate an invoice being sent to their email address and the suspension date of their profile will be set to five calendar days from the date of profile creation.  The suspension date will be extended by an administrator if payment for the invoice is received.
•	
Work Seeker:
•	Date of birth
•	Physical Address
•	SARS Tax No, if applicable
•	Job title – if they choose “Other”, pop up a text field for them to complete and add it to the table
•	Bank account details
o	Bank name
o	Bank Branch Name (optional)
o	Bank Branch Number
o	Account name
o	Account number
o	Account type (Current, Savings, Credit Card)
o	Account holder relationship (Self, Joint, 3rd Party)
•	Preferences:
o	Working hours per week
o	Working hours per day
o	Available working times – starting from, day/night shift, available days in the week
o	Wages per hour or Salary per month
o	Payment Frequency (weekly / monthly
•	Documents to be uploaded – use the same intersection table as listed for Business and Personal Clients:
o	Image of ID Document
o	Head and shoulders photograph
o	Police clearance certificate
o	Competence certificates, if any
o	References, if any
•	There is no cost for registering as a work seeker, but the profile and availability of the work seeker will not be displayed to Clients until the Images of ID Document, Head and shoulders photograph, and Police clearance certificate have been uploaded.
Registered Administrators (us, Sunshine Coast NGO, other partners) can register a domestic worker (cleaner, cook, gardener, Home Based Care Carer) or other staff (nursing professionals, small business staff, independent contractors) at no charge.
Work seekers (or registered users on their behalf) can enter available times when they can work, in a daily pattern (eg Friday day shift, or Mondays, Thursdays, and Fridays from 08h00 to 18h00) or date period (eg from 08 May 2026 onwards).  Free if they do it themselves, admin fee if Admin must do it from personal visit, phone, WhatsApp, or email message.  Penalty if availability is not up to date and they are selected for a gig – this will be done by an administrator.  Availability and profile details will be available to registered Clients on the website.
Registered people and the public can browse the website at no charge, but only blogs and class of employee and number available in that category are shown.  Registered people and Clients are only shown to Administrators and the individuals themselves.  If they register and submit a client profile, they get to search for what they want and view names, qualifications, CVs, etc, and select work-seekers to interview and the work-seekers get the Client’s name and contact email and phone number to initiate contact for interview via email.  We generate a charge for every work seeker selected and invoice the Client.  If the interview is unsuccessful, Client can interview again, until they choose an employee.  Client can then ignore us and pay employee herself, or can subscribe to payroll profile:
•	Select the employee
•	Salary agreed
•	Payment frequency
•	Payment day (if weekly paid) or day in the month (for monthly paid)
•	Annual bonus – month and amount, or percentage of monthly income – can be zero percent
•	Leave rate agreed – minimum 1/17 hours worked (if employee works more than 24 hours in the month), but could be more - consult relevant legislation
•	Conditions for commutation of leave to cash, if allowed by law
•	Pension / provident contribution – Client and employee – with details of fund
•	Medical aid contribution – Client and employee – with details of medical aid
We predict and show gross cost (our fee, salary, 1% for Client UIF and SDL,
COID, provision for annual bonus, provision for leave (vacational, sick, paternity, etc) with recommendation that provisions be paid into a notice deposit account in the name of the employer.
Employer submits time sheets for employees each month, we calculate pay and send him payslip and schedule of other payments to be made, plus our invoice.  Timesheet fields include:
•	Employee
•	Period from
•	Period to
•	Date worked
•	Normal Hours
•	Overtime hours
•	Confirmation checkbox and release for payroll processing, after which, it is locked and can only be changed by an administrator.
We lock payroll features if his previous invoice from us has not been paid.
We need a facility to communicate with individuals (individuals or groups of Clients and workers and potential workers) using email, WhatsApp, or SMS) 
Ask questions if you need information or if, in your judgement, the specification should change.
