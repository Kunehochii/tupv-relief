# RELIEF - Specification Document

> Spec-Driven Development Tracking for the Relief Donation Drive Management System

**Version:** 1.1  
**Last Updated:** January 16, 2026  
**Status:** In Development

---

## Overview

This document tracks the implementation progress of RELIEF features based on the Software Requirements Specification (RELIEF_SRS.md). Each feature includes acceptance criteria and implementation status.

---

## Implementation Status Legend

| Status      | Symbol | Description                  |
| ----------- | ------ | ---------------------------- |
| Not Started | ⬜     | Feature not yet implemented  |
| In Progress | 🔄     | Currently being developed    |
| Completed   | ✅     | Fully implemented and tested |
| Blocked     | 🚫     | Blocked by dependencies      |

---

## Module 1: Authentication & User Management

### 1.1 User Registration

| ID      | Feature                                         | Status | Notes                                          |
| ------- | ----------------------------------------------- | ------ | ---------------------------------------------- |
| AUTH-01 | Email/password registration with role selection | ✅     | RegisteredUserController ready                 |
| AUTH-02 | Google OAuth sign-in                            | ✅     | GoogleController ready, needs Socialite config |
| AUTH-03 | Role-based redirect after login                 | ✅     | Logic in AuthenticatedSessionController        |
| AUTH-04 | NGO certificate upload during registration      | ✅     | File upload to certificates/                   |
| AUTH-05 | Email verification for all users                | ✅     | Laravel MustVerifyEmail                        |

**Acceptance Criteria:**

- [ ] User can register with email/password
- [ ] User can register/login via Google OAuth
- [ ] NGO users can upload certificate during registration
- [ ] Users are redirected to appropriate dashboard based on role
- [ ] Email verification is enforced for new accounts

### 1.2 Admin Seeding

| ID      | Feature                          | Status | Notes               |
| ------- | -------------------------------- | ------ | ------------------- |
| SEED-01 | Default admin account via seeder | ✅     | AdminSeeder created |

**Acceptance Criteria:**

- [ ] Running `php artisan db:seed` creates admin@relief.dswd.gov.ph
- [ ] Admin can login immediately after seeding

---

## Module 2: Admin Portal (DSWD)

### 2.1 Dashboard

| ID       | Feature                     | Status | Notes                           |
| -------- | --------------------------- | ------ | ------------------------------- |
| ADMIN-01 | Overview statistics display | ✅     | admin/dashboard.blade.php ready |
| ADMIN-02 | Quick action links          | ✅     | Links to pending pledges, NGOs  |
| ADMIN-03 | Recent activity feed        | ✅     | Show recent pledges             |

**Acceptance Criteria:**

- [ ] Dashboard shows total drives, pledges, NGOs count
- [ ] Dashboard shows pending items requiring attention
- [ ] Recent pledges list is visible

### 2.2 Drive Management

| ID       | Feature                         | Status | Notes                                   |
| -------- | ------------------------------- | ------ | --------------------------------------- |
| DRIVE-01 | Create new drive with details   | ✅     | DriveController@store                   |
| DRIVE-02 | Set drive location on map       | ✅     | Leaflet integration in create.blade.php |
| DRIVE-03 | Edit existing drives            | ✅     | DriveController@update                  |
| DRIVE-04 | View drive details with pledges | ✅     | drives/show.blade.php ready             |
| DRIVE-05 | Mark drive as completed         | ✅     | DriveController@complete                |
| DRIVE-06 | List all drives with filters    | ✅     | drives/index.blade.php implemented      |
| DRIVE-07 | Map overview of all drives      | ✅     | admin/drives/map.blade.php with Leaflet |

**Acceptance Criteria:**

- [x] Admin can create drive with name, description, target, dates
- [x] Admin can pin location on OpenStreetMap
- [x] Drive shows progress percentage
- [x] Admin can edit drive details
- [x] Admin can manually update collected amount (progress)
- [x] Admin can mark drive as completed
- [x] Admin can view all drives on interactive map

### 2.3 Pledge Management

| ID        | Feature                        | Status | Notes                                   |
| --------- | ------------------------------ | ------ | --------------------------------------- |
| PLEDGE-01 | View all pledges with filters  | ✅     | pledges/index.blade.php ready           |
| PLEDGE-02 | View individual pledge details | ✅     | pledges/show.blade.php ready            |
| PLEDGE-03 | Verify pending pledges         | ✅     | PledgeController@verify                 |
| PLEDGE-04 | Mark pledge as distributed     | ✅     | PledgeController@distribute             |
| PLEDGE-05 | Add impact feedback            | ✅     | families_helped, relief_packages fields |
| PLEDGE-06 | Reject pledge with reason      | ✅     | PledgeController@reject                 |

**Acceptance Criteria:**

- [ ] Admin can filter pledges by status, drive
- [ ] Admin can verify pledge and send notification
- [ ] Admin can mark pledge distributed with impact data
- [ ] Impact feedback (families helped, packages) is recorded

### 2.4 NGO Verification

| ID     | Feature                       | Status | Notes                             |
| ------ | ----------------------------- | ------ | --------------------------------- |
| NGO-01 | View pending NGO applications | ✅     | ngos/pending.blade.php ready      |
| NGO-02 | Review certificate document   | ✅     | Certificate preview/download      |
| NGO-03 | Approve NGO organization      | ✅     | NgoVerificationController@approve |
| NGO-04 | Reject NGO with reason        | ✅     | NgoVerificationController@reject  |

**Acceptance Criteria:**

- [ ] Admin sees list of pending NGOs
- [ ] Admin can view uploaded certificate
- [ ] Approved NGO can make pledges
- [ ] Rejected NGO receives notification with reason

### 2.5 Reports

| ID        | Feature                   | Status | Notes                                    |
| --------- | ------------------------- | ------ | ---------------------------------------- |
| REPORT-01 | Overview statistics       | ✅     | reports/index.blade.php with stats cards |
| REPORT-02 | Drive performance metrics | ✅     | Progress, pledge counts table            |
| REPORT-03 | NGO contribution tracking | ✅     | Including link clicks in NGO table       |
| REPORT-04 | Export to CSV/Excel       | ✅     | ReportController@export with CSV support |

**Acceptance Criteria:**

- [x] Reports show total donations (financial + in-kind)
- [x] Reports show families helped
- [x] Admin can export pledge data
- [x] NGO link click statistics are visible

---

## Module 3: Donor Portal

### 3.1 Dashboard

| ID       | Feature                 | Status | Notes                           |
| -------- | ----------------------- | ------ | ------------------------------- |
| DONOR-01 | View active drives list | ✅     | donor/dashboard.blade.php ready |
| DONOR-02 | See own pledge summary  | ✅     | Pledge counts by status         |
| DONOR-03 | Quick pledge action     | ✅     | Button to create pledge         |

**Acceptance Criteria:**

- [ ] Donor sees active drives with progress
- [ ] Donor sees their pledge statistics
- [ ] Donor can quickly navigate to pledge creation

### 3.2 Pledge Management

| ID         | Feature                         | Status | Notes                          |
| ---------- | ------------------------------- | ------ | ------------------------------ |
| DPLEDGE-01 | Create new pledge               | ✅     | pledges/create.blade.php ready |
| DPLEDGE-02 | Choose financial or in-kind     | ✅     | Type selection form            |
| DPLEDGE-03 | View pledge list                | ✅     | pledges/index.blade.php ready  |
| DPLEDGE-04 | View pledge details with status | ✅     | pledges/show.blade.php ready   |
| DPLEDGE-05 | Receive reference number        | ✅     | Auto-generated on creation     |

**Acceptance Criteria:**

- [x] Donor can pledge to specific drive
- [x] Financial pledges capture amount
- [x] In-kind pledges capture quantity and description
- [x] Reference number is displayed after submission

### 3.3 Map View

| ID     | Feature                     | Status | Notes                     |
| ------ | --------------------------- | ------ | ------------------------- |
| MAP-01 | View all drives on map      | ✅     | donor/map.blade.php ready |
| MAP-02 | Click marker for drive info | ✅     | Popup with details        |
| MAP-03 | Navigate to pledge from map | ✅     | Link in popup             |

**Acceptance Criteria:**

- [ ] Map displays all active drives with markers
- [ ] Clicking marker shows drive summary
- [ ] Donor can pledge directly from map popup

### 3.4 Notifications

| ID       | Feature                     | Status | Notes                               |
| -------- | --------------------------- | ------ | ----------------------------------- |
| NOTIF-01 | View notification list      | ✅     | notifications/index.blade.php ready |
| NOTIF-02 | Mark as read                | ✅     | NotificationController@markAsRead   |
| NOTIF-03 | Receive email notifications | ✅     | NotificationService + SendGrid      |

**Acceptance Criteria:**

- [ ] Donor receives notification when pledge verified
- [ ] Donor receives notification when pledge distributed
- [ ] Email sent via SendGrid for important events
- [ ] Notifications show read/unread status

---

## Module 4: NGO Portal

### 4.1 Verification Flow

| ID      | Feature                           | Status | Notes                            |
| ------- | --------------------------------- | ------ | -------------------------------- |
| NGOV-01 | Show verification status banner   | ✅     | ngo/dashboard.blade.php ready    |
| NGOV-02 | Restrict features until verified  | ✅     | VerifiedNgo middleware           |
| NGOV-03 | Receive verification notification | ✅     | NotificationService handles this |

**Acceptance Criteria:**

- [ ] Pending NGO sees "Awaiting Verification" banner
- [ ] Verified NGO can access all features
- [ ] Rejected NGO sees reason and can reapply

### 4.2 Pledge Management

| ID      | Feature                      | Status | Notes                           |
| ------- | ---------------------------- | ------ | ------------------------------- |
| NGOP-01 | Make pledges (verified only) | ✅     | verified.ngo middleware applied |
| NGOP-02 | View organization pledges    | ✅     | NGO-specific pledge views       |
| NGOP-03 | Track pledge status          | ✅     | Same flow as donor              |

**Acceptance Criteria:**

- [x] Verified NGO can pledge to drives
- [x] NGO pledges marked with organization name
- [x] Same notification flow as donors

### 4.3 External Donation Link

| ID      | Feature                   | Status | Notes                               |
| ------- | ------------------------- | ------ | ----------------------------------- |
| LINK-01 | Set external donation URL | ✅     | donation-link/index.blade.php ready |
| LINK-02 | Track link clicks         | ✅     | LinkClick model, tracking route     |
| LINK-03 | View click statistics     | ✅     | Count + recent clicks shown         |

**Acceptance Criteria:**

- [ ] NGO can set their donation page URL
- [ ] Public link redirects through tracking
- [ ] Click count and history visible to NGO
- [ ] Admin can see NGO click metrics in reports

---

## Module 5: Public Features

### 5.1 Landing Page

| ID     | Feature                         | Status | Notes                   |
| ------ | ------------------------------- | ------ | ----------------------- |
| PUB-01 | Display active drives           | ✅     | welcome.blade.php ready |
| PUB-02 | Show drive progress             | ✅     | Progress bars on cards  |
| PUB-03 | Call-to-action for registration | ✅     | Login/Register buttons  |

**Acceptance Criteria:**

- [ ] Visitors see active drives on homepage
- [ ] Drive cards show name, description, progress
- [ ] Clear path to registration

### 5.2 Drive Preview Page

| ID      | Feature                     | Status | Notes                                              |
| ------- | --------------------------- | ------ | -------------------------------------------------- |
| PREV-01 | Public shareable drive page | ✅     | public/drive-preview.blade.php ready               |
| PREV-02 | Display drive details       | ✅     | All drive info shown                               |
| PREV-03 | Show location map           | ✅     | Leaflet map embedded                               |
| PREV-04 | CTA for logged-in users     | ✅     | Pledge button for donor/NGO, manage link for admin |

**Acceptance Criteria:**

- [x] Drive preview accessible without login
- [x] Shows drive name, description, progress, location
- [x] Authenticated users see pledge button
- [x] Guests see login/register prompt

---

## Module 6: Background Jobs

### 6.1 Scheduled Tasks

| ID     | Feature                           | Status | Notes                      |
| ------ | --------------------------------- | ------ | -------------------------- |
| JOB-01 | Expire old pending pledges        | ⬜     | ExpirePledges command      |
| JOB-02 | Send expiry warning notifications | ⬜     | SendExpiryWarnings command |
| JOB-03 | Schedule in Laravel               | ⬜     | console.php configured     |

**Acceptance Criteria:**

- [ ] Pledges pending >7 days auto-expire
- [ ] 3-day warning notification sent
- [ ] Jobs run via scheduler

---

## Module 7: Email Notifications

### 7.1 SendGrid Integration

| ID       | Feature                      | Status | Notes                               |
| -------- | ---------------------------- | ------ | ----------------------------------- |
| EMAIL-01 | Configure SendGrid SMTP      | ✅     | .env.example has placeholders       |
| EMAIL-02 | Notification email template  | ✅     | emails/notification.blade.php ready |
| EMAIL-03 | Send on pledge status change | ✅     | NotificationService handles         |

**Acceptance Criteria:**

- [ ] MAIL_MAILER=smtp with SendGrid credentials
- [ ] Emails styled with Relief branding
- [ ] Emails sent for: verification, distribution, expiry warning

---

## Technical Debt & Improvements

| ID    | Item                         | Priority | Notes                     |
| ----- | ---------------------------- | -------- | ------------------------- |
| TD-01 | Add comprehensive validation | High     | Request classes for forms |
| TD-02 | Implement caching            | Medium   | Cache drive queries       |
| TD-03 | Add API endpoints            | Low      | For future mobile app     |
| TD-04 | Write feature tests          | High     | PHPUnit/Pest tests        |
| TD-05 | Add audit logging            | Medium   | Track admin actions       |

---

## Environment Setup Checklist

Before running the application:

- [ ] Copy `.env.example` to `.env`
- [ ] Run `composer install`
- [ ] Run `php artisan key:generate`
- [ ] Configure database in `.env`
- [ ] Run `php artisan migrate`
- [ ] Run `php artisan db:seed`
- [ ] Configure SendGrid credentials
- [ ] Configure Google OAuth credentials
- [ ] Run `npm install && npm run build` (if using Vite)

---

## Notes

- All routes requiring authentication use appropriate middleware
- Role-based access controlled via `admin`, `donor`, `ngo` middleware
- NGO-specific features gated by `verified.ngo` middleware
- Map integration uses OpenStreetMap via Leaflet.js (no API key required)
- Email notifications use SendGrid SMTP

---

_This spec document should be updated as features are implemented. Mark items as ✅ when tests pass and feature is deployed._
