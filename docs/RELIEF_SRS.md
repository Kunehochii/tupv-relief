# **Software Requirements Specification (SRS)**

**Project Name:** Relief

**Version:** 2.0

**Status:** Draft \- Increment 7

**Last Updated:** January 15, 2026

---

## **1\. Module: Public Landing Page**

### **1.1 Overview**

The entry point for all unauthenticated users. It serves to inform the public about the platform's purpose and route them to the correct registration flow.

### **1.2 Functional Requirements**

| ID             | Requirement Name     | Description                                                                                                                              | Priority |
| :------------- | :------------------- | :--------------------------------------------------------------------------------------------------------------------------------------- | :------- |
| **FR-LAND-01** | **Hero & CTA**       | The landing page shall feature a Hero section with a clear "Donate Now" or "Get Started" Call-to-Action (CTA).                           | High     |
| **FR-LAND-02** | **Role Information** | The page must explicitly display information sections for **NGOs**, **DSWD**, and **Donors**, explaining the value proposition for each. | High     |
| **FR-LAND-03** | **Feature Showcase** | The page shall list key features to encourage sign-ups.                                                                                  | Medium   |

### **1.3 Page: Public Drive Preview**

Public-facing page for shared campaign links.

| ID             | Requirement Name   | Description                                                                                                      | Priority |
| :------------- | :----------------- | :--------------------------------------------------------------------------------------------------------------- | :------- |
| **FR-LAND-04** | **Drive Preview**  | Shared links shall lead to a public "Drive Preview" page showing drive details without requiring authentication. | High     |
| **FR-LAND-05** | **Auth Prompt**    | The preview page shall prompt Login/Signup before allowing pledge action.                                        | High     |
| **FR-LAND-06** | **Drive Map View** | The preview page shall display the drive location on an embedded OpenStreetMap.                                  | Medium   |

## **2\. Module: User Authentication**

### **2.1 Overview**

Governs secure entry. Supports **User Login** and **User Registration** (Donor/NGO).

**Constraints:**

1. **Admins** cannot register via UI (Database Seeded).
2. **Donors/NGOs** can self-register.

### **2.2 Functional Requirements**

| ID             | Requirement Name           | Description                                                                          | Priority |
| :------------- | :------------------------- | :----------------------------------------------------------------------------------- | :------- |
| **FR-AUTH-01** | **Role Selection**         | Registration must allow selecting "Donor" or "NGO Partner".                          | High     |
| **FR-AUTH-02** | **Google OAuth**           | Support for Google Sign-In/Sign-Up for Donors and NGOs.                              | High     |
| **FR-AUTH-03** | **Manual Auth**            | Manual Email/Password registration.                                                  | High     |
| **FR-AUTH-04** | **NGO Certificate Upload** | NGO registration must require upload of a Certificate of Authenticity (PDF/Image).   | Critical |
| **FR-AUTH-05** | **NGO Pending Status**     | NGO accounts shall be created in "Pending Verification" status until admin approval. | Critical |
| **FR-AUTH-06** | **NGO Verification Email** | System shall send email notification when NGO account is verified or rejected.       | High     |

## **3\. Module: Admin Panel**

### **3.1 Page: Dashboard Overview**

The command center for platform administrators.

| ID              | Requirement Name   | Description                                                                                                                      | Priority |
| :-------------- | :----------------- | :------------------------------------------------------------------------------------------------------------------------------- | :------- |
| **FR-ADMIN-01** | **Global Metrics** | Dashboard shall display counters for: **Total Drives**, **Active Drives**, **Total Donations**, and **Pending Verifications**.   | High     |
| **FR-ADMIN-02** | **Quick Lists**    | Dashboard shall display a table of "Active Donation Drives" and a list of "Pending Verifications" requiring immediate attention. | High     |

### **3.2 Page: Pending NGO Verifications**

Interface to verify NGO registrations.

| ID              | Requirement Name          | Description                                                                                       | Priority |
| :-------------- | :------------------------ | :------------------------------------------------------------------------------------------------ | :------- |
| **FR-ADMIN-03** | **NGO Verification List** | Display list of pending NGO registrations with certificate preview.                               | Critical |
| **FR-ADMIN-04** | **NGO Verify/Reject**     | Admin shall be able to **Approve** or **Reject** NGO registration with optional rejection reason. | Critical |
| **FR-ADMIN-05** | **Certificate View**      | Admin can view/download uploaded Certificate of Authenticity.                                     | High     |

### **3.3 Page: Create Drive**

Interface to initialize a new campaign.

| ID              | Requirement Name     | Description                                                                                                                | Priority |
| :-------------- | :------------------- | :------------------------------------------------------------------------------------------------------------------------- | :------- |
| **FR-ADMIN-06** | **Drive Fields**     | Form must capture: **Drive Name**, **Description**, **Target Amount** (Financial/Qty), **End Date**, and **Items Needed**. | Critical |
| **FR-ADMIN-07** | **Drive Location**   | Admin shall pin drive location on an interactive OpenStreetMap (latitude/longitude).                                       | High     |
| **FR-ADMIN-08** | **Location Address** | System shall store human-readable address alongside coordinates.                                                           | Medium   |

### **3.4 Page: Manage Drives**

Interface to list and control existing campaigns.

| ID              | Requirement Name  | Description                                                                              | Priority |
| :-------------- | :---------------- | :--------------------------------------------------------------------------------------- | :------- |
| **FR-ADMIN-09** | **Drive Actions** | Admin shall be able to **Edit**, **View Details**, and **Close** (End) a drive manually. | High     |
| **FR-ADMIN-10** | **Drive List**    | Filterable list showing Active and Completed drives.                                     | Medium   |
| **FR-ADMIN-11** | **Map Overview**  | Admin can view all drives on a single map.                                               | Low      |

### **3.5 Page: Verify Donations**

Interface to validate physical receipts against digital pledges.

| ID              | Requirement Name        | Description                                                                              | Priority |
| :-------------- | :---------------------- | :--------------------------------------------------------------------------------------- | :------- |
| **FR-ADMIN-12** | **Verification Action** | Admin shall be able to **Approve** a specific donation pledge after viewing its details. | Critical |

### **3.6 Page: Pledge Feedback**

Interface to provide impact feedback on verified pledges.

| ID              | Requirement Name          | Description                                                                                               | Priority |
| :-------------- | :------------------------ | :-------------------------------------------------------------------------------------------------------- | :------- |
| **FR-ADMIN-13** | **Impact Input**          | Admin shall manually input **Families Helped**, **Relief Packages**, and other impact metrics per pledge. | High     |
| **FR-ADMIN-14** | **Feedback Notification** | System shall notify donor when feedback is added to their pledge.                                         | Medium   |

### **3.7 Page: Reports**

Analytics generation.

| ID              | Requirement Name | Description                                                                                                                          | Priority |
| :-------------- | :--------------- | :----------------------------------------------------------------------------------------------------------------------------------- | :------- |
| **FR-ADMIN-15** | **Report Types** | System must generate reports for: **Donation Summary**, **Drive Performance**, **Donor Statistics**, and **Financial Transparency**. | Medium   |

## **4\. Module: Donor Portal**

### **4.1 Page: Dashboard & Available Drives**

The main view for a logged-in donor.

| ID              | Requirement Name   | Description                                                                                                            | Priority |
| :-------------- | :----------------- | :--------------------------------------------------------------------------------------------------------------------- | :------- |
| **FR-DONOR-01** | **Personal Cards** | Top of page shall show donor's specific stats: **My Pledges**, **Verified Count**, **Pending Count**, **Total Value**. | High     |
| **FR-DONOR-02** | **Active Drives**  | A list of available drives. Each card must have a "Pledge to this Drive" button.                                       | Critical |
| **FR-DONOR-03** | **Drive Map View** | Display an interactive map showing all active drive locations.                                                         | Medium   |

### **4.2 Page: Pledge Donation Form**

The transactional interface.

| ID              | Requirement Name    | Description                                                                                           | Priority |
| :-------------- | :------------------ | :---------------------------------------------------------------------------------------------------- | :------- |
| **FR-DONOR-04** | **Drive Selection** | If accessed via Navbar, allow search/select Drive. If accessed via Drive Card, pre-select that Drive. | High     |
| **FR-DONOR-05** | **Pledge Fields**   | Form must capture: **Items**, **Quantity/Details**, **Contact Number**, **Additional Notes**.         | Critical |
| **FR-DONOR-06** | **Reference Gen**   | Upon submission, generate and display a **Unique Reference Number**.                                  | Critical |

### **4.3 Page: My Pledges**

Personal history and impact.

| ID              | Requirement Name   | Description                                                                            | Priority |
| :-------------- | :----------------- | :------------------------------------------------------------------------------------- | :------- |
| **FR-DONOR-07** | **Pledge History** | List of all pledges with statuses. Must allow "View Receipt".                          | High     |
| **FR-DONOR-08** | **Impact Stats**   | Display admin-provided impact feedback: **Families Helped** and **Items Distributed**. | Medium   |
| **FR-DONOR-09** | **Feedback View**  | View detailed feedback from admin on verified pledges.                                 | Medium   |

### **4.4 Feature: Notifications**

Alert system with in-app and email delivery via SendGrid.

| ID              | Requirement Name        | Description                                                                                                                                                                           | Priority |
| :-------------- | :---------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | :------- |
| **FR-DONOR-10** | **Notif Types**         | Trigger alerts for: **Pledge Acknowledgement**, **New Drive Available**, **Verification Updates**, **Pledge Expiry Warning**, **Donation Distributed**, **Impact Feedback Received**. | High     |
| **FR-DONOR-11** | **Email Notifications** | Critical notifications (Pledge Expiry, Verification, Distribution) shall be sent via email using SendGrid.                                                                            | High     |
| **FR-DONOR-12** | **Color Coding**        | Visual indicators based on type (e.g., Green for Verified, Red for Expired, Blue for New Drive, Purple for Distributed).                                                              | Low      |

## **5\. Module: NGO Portal**

### **5.1 Page: Active Drives & Dashboard**

Similar to Donor view but tailored for Organizations.

| ID            | Requirement Name        | Description                                                                 | Priority |
| :------------ | :---------------------- | :-------------------------------------------------------------------------- | :------- |
| **FR-NGO-01** | **Org Dashboard**       | Cards showing: **Our Pledges**, **Verified**, **Pending**, **Total Value**. | High     |
| **FR-NGO-02** | **Pledge Action**       | NGOs can pledge to drives similar to individual donors.                     | High     |
| **FR-NGO-03** | **Drive Map View**      | Display an interactive map showing all active drive locations.              | Medium   |
| **FR-NGO-04** | **Verification Status** | Show NGO account verification status banner if pending.                     | High     |

### **5.2 Page: Pledge Donations & Impact**

Tracking the NGO's contribution to the ecosystem.

| ID            | Requirement Name  | Description                                                                                                   | Priority |
| :------------ | :---------------- | :------------------------------------------------------------------------------------------------------------ | :------- |
| **FR-NGO-05** | **Org Impact**    | Dashboard showing admin-provided feedback: **Families Helped**, **Relief Packages**, **Drives Participated**. | Medium   |
| **FR-NGO-06** | **Quick Actions** | Shortcuts for: "Pledge New Donation", "Manage External Link", "Share Campaign".                               | Low      |

### **5.3 Page: Manage Donation Link**

External integration management.

| ID            | Requirement Name | Description                                                                                        | Priority |
| :------------ | :--------------- | :------------------------------------------------------------------------------------------------- | :------- |
| **FR-NGO-07** | **Custom Link**  | Form to setup/update the organization's external donation URL.                                     | High     |
| **FR-NGO-08** | **Link Stats**   | Display metrics for the custom link: **Click Count** (conversion tracking limited to clicks only). | Medium   |

### **5.4 Feature: NGO Notifications**

| ID            | Requirement Name         | Description                                                                                     | Priority |
| :------------ | :----------------------- | :---------------------------------------------------------------------------------------------- | :------- |
| **FR-NGO-09** | **Account Verified**     | Email notification when NGO account is approved by admin.                                       | High     |
| **FR-NGO-10** | **Account Rejected**     | Email notification with rejection reason when NGO account is rejected.                          | High     |
| **FR-NGO-11** | **Pledge Notifications** | Same notification types as Donors (Pledge Acknowledgement, Verification, Expiry, Distribution). | High     |

## **6\. Module: Automated Notifications (Backend)**

All notifications are delivered both in-app and via email using SendGrid SMTP.

| ID              | Notification Type        | Trigger                              | Recipients       | Priority |
| :-------------- | :----------------------- | :----------------------------------- | :--------------- | :------- |
| **FR-NOTIF-01** | **18-Hour Reminder**     | Unverified pledge approaching expiry | Donor/NGO        | High     |
| **FR-NOTIF-02** | **24-Hour Expiry**       | Pledge auto-expired                  | Donor/NGO        | High     |
| **FR-NOTIF-03** | **Pledge Acknowledged**  | New pledge submitted                 | Donor/NGO        | Medium   |
| **FR-NOTIF-04** | **Pledge Verified**      | Admin verifies pledge                | Donor/NGO        | High     |
| **FR-NOTIF-05** | **Donation Distributed** | Admin marks donation as distributed  | Donor/NGO        | High     |
| **FR-NOTIF-06** | **Impact Feedback**      | Admin adds impact feedback           | Donor/NGO        | Medium   |
| **FR-NOTIF-07** | **New Drive Available**  | New drive created                    | All Donors/NGOs  | Low      |
| **FR-NOTIF-08** | **NGO Account Verified** | Admin approves NGO                   | NGO              | Critical |
| **FR-NOTIF-09** | **NGO Account Rejected** | Admin rejects NGO                    | NGO              | Critical |
| **FR-NOTIF-10** | **Drive Ending Soon**    | Drive ends in 24 hours               | Subscribed Users | Low      |

## **7\. Module: Map Feature**

Interactive mapping using OpenStreetMap (via Leaflet.js).

| ID            | Requirement Name       | Description                                                                    | Priority |
| :------------ | :--------------------- | :----------------------------------------------------------------------------- | :------- |
| **FR-MAP-01** | **Admin Pin Location** | Admin can place/move a pin on map when creating/editing drives.                | High     |
| **FR-MAP-02** | **Coordinate Storage** | System stores latitude, longitude, and address for each drive.                 | High     |
| **FR-MAP-03** | **Public Map View**    | All active drives displayed on a map on public drive preview.                  | Medium   |
| **FR-MAP-04** | **Portal Map View**    | Donors and NGOs can view all drives on an interactive map in their dashboards. | Medium   |
| **FR-MAP-05** | **Map Clustering**     | When multiple drives are nearby, cluster markers for better UX.                | Low      |

## **8\. Technological Stack**

| Component          | Technology                                |
| :----------------- | :---------------------------------------- |
| **Framework**      | Laravel 11 (PHP 8.2+)                     |
| **Database**       | MySQL 8.0                                 |
| **Frontend**       | Blade Templates, Bootstrap 5, Leaflet.js  |
| **Authentication** | Laravel Breeze + Socialite (Google OAuth) |
| **Email Service**  | SendGrid (SMTP)                           |
| **Maps**           | OpenStreetMap via Leaflet.js              |
| **File Storage**   | Local (certificates, receipts)            |

## **9\. Resolved Questions**

Previously open questions that have been resolved:

### **9.1 Impact Calculation Logic**

**Resolution:** DSWD Admin will manually input impact metrics (Families Helped, Relief Packages, Items Distributed) through a "Pledge Feedback" feature for each verified donation. No automatic conversion formulas required.

### **9.2 Conversion Rate Tracking**

**Resolution:** System will track **Link Clicks only** for NGO external donation links. Full conversion tracking would require third-party API integration which is out of scope for initial release.

### **9.3 Financial Transparency Reports**

**Status:** Open - Requires clarification on whether Target Amount refers to monetary value or item quantity estimation.

### **9.4 Share Campaign Mechanics**

**Resolution:** Shared links lead to a **Public Drive Preview Page** that displays drive information without authentication. Users are prompted to Login/Signup before pledging.
