# উপলব্ধি সমবায় সমিতি (USS) — Web Application

## সিস্টেম ওভারভিউ (System Overview)

**উপলব্ধি সমবায় সমিতি (USS)** is a private cooperative installment management web application built with **Laravel 11 + Statamic CMS** (flat-file, no database required). It manages monthly contributions, investments, events, rules, and financial transparency for 6 founding friends.

---

## 🔑 Login Credentials

### Member Portal (Website Login — All Members Use password123)

| ভূমিকা (Role) | নাম (Name) | Email | Password |
|---|---|---|---|
| **সভাপতি (President / Super Admin)** | সজিব মোল্লা (Sajib Mulla) | `sajib@upolobdi.org` | `password123` |
| **সহ-সভাপতি (Vice President / Admin)** | রুবেল মোল্লা (Rubel Mulla) | `rubel@upolobdi.org` | `password123` |
| **ক্যাশিয়ার (Treasurer / Cashier)** | সাইফুল ইসলাম (Saiful Islam) | `saiful@upolobdi.org` | `password123` |
| **সদস্য (Member)** | কাউসার (Kawser) | `kawser@upolobdi.org` | `password123` |
| **সদস্য (Member)** | দৌলত (Doulot) | `doulot@upolobdi.org` | `password123` |
| **সদস্য (Member)** | ফেরদৌস (Ferdous) | `ferdous@upolobdi.org` | `password123` |

### Statamic Control Panel (CP) Superadmin Login

> ⚠️ **The Statamic Control Panel (`/cp`) has dedicated administrator credentials.**  
> For daily somiti operations, all committee members and founders use the Member Portal above.

| CP URL | Email | Password |
|---|---|---|
| `http://127.0.0.1:8000/cp` | `superadmin@upolobdi.org` | `Secret123` |

---

## 👑 How Superadmin Can Change a User's Role (Via Control Panel)

Only the **Dedicated Superadmin** has full administrative authority in the Statamic Control Panel (`/cp`) to reassign committee positions and roles for existing members:

### Step-by-Step Instructions:

1. **Access the Control Panel**:
   - Navigate to `http://127.0.0.1:8000/cp` in your browser.
   - Log in using Superadmin credentials:
     - **Email**: `superadmin@upolobdi.org`
     - **Password**: `Secret123`

2. **Navigate to Users Management**:
   - From the left sidebar menu, click on **Users** (or go directly to `http://127.0.0.1:8000/cp/users`).

3. **Select the Member to Modify**:
   - Click on the name/email of the user whose role you want to change (e.g., `sajib@upolobdi.org`, `rubel@upolobdi.org`, `saiful@upolobdi.org`, `kawser@upolobdi.org`, etc.).

4. **Change Role & Permissions**:
   - In the user edit screen, locate the **Roles** field:
     - Check **`superadmin`** to promote the user to **সভাপতি (President / Super Admin)**.
     - Check **`admin`** to assign as **সহ-সভাপতি (Vice President / Admin)**.
     - Check **`cashier`** to assign as **ক্যাশিয়ার (Treasurer / Cashier)**.
     - Leave only **`member`** (or uncheck other roles) for a **কার্যনির্বাহী সদস্য (General Member)**.

5. **Update Designation (পদবী)**:
   - In the **Designation** text box, update the title to reflect their official role:
     - President: `সভাপতি (President)`
     - Vice President: `সহ-সভাপতি (Vice President)`
     - Cashier: `ক্যাশিয়ার (Cashier)`
     - Member: `কার্যনির্বাহী সদস্য (Member)`

6. **Save Changes**:
   - Click the blue **Save** button in the top right corner.
   - The changes take effect immediately across both the public website and the member dashboard.

> 💡 **Tip for Direct Disk/YAML Changes**: If modifying user YAML files directly inside the `users/` folder, run `php artisan statamic:stache:clear` afterwards to refresh Statamic's cache.


## 🏗️ Architecture & Technology Stack

- **Framework**: Laravel 11 (PHP)
- **CMS**: Statamic 4 (Flat-File — no MySQL required)
- **Storage**: YAML files in `content/` and `users/` directories
- **Frontend**: Blade Templates + Tailwind CSS CDN + Three.js (3D Medallion)
- **Auth**: Statamic built-in user authentication
- **Server**: PHP built-in dev server OR Apache/Nginx

---

## 🚀 Running the Application

```bash
# Start the development server
php -S 127.0.0.1:8000 -t public

# Clear all caches
php artisan statamic:stache:clear
php artisan config:clear
php artisan cache:clear

# Seed mock investment projects
php artisan somiti:seed-projects

# Seed mock events (if needed)
php artisan somiti:seed
```

---

## 📋 Sections & Features

### Public Website (`/`)

| Section | Description |
|---|---|
| **Hero** | 3D rotating USS emblem medallion with bilingual motto |
| **Members Carousel** | Auto-scrolling card carousel of all 6 friends |
| **Investment Projects** | Ongoing & planned ventures added by President/VP |
| **Highlights Gallery** | Curated images selected from events by Admin |
| **Masonry Event Gallery** | Photo-only grid from events selected for masonry (no titles shown) |
| **Financial Simulator** | Installment & savings projection calculator |
| **Bylaws & Rules** | Current somiti rules managed by President/VP |
| **Payment Accounts** | bKash, Nagad, Rocket, Bank details |
| **Footer** | Bilingual, editable by President |

> **Privacy**: Total investment amount is hidden from public view (blurred server-side using CSS class `filter blur` — not removable via browser inspect).

---

### Member Portal (`/dashboard`)

#### All Roles (Members, Cashier, VP, President)
- ✅ View personal due alert (overdue installments)
- ✅ Submit monthly installment slip (amount + screenshot)
- ✅ View personal ledger/payment history
- ✅ Change profile info and password

#### Cashier (সাইফুল ইসলাম) — Extra Access
- ✅ Review & approve/reject payment slips from members
- ✅ Adjust verified amount & months before approving
- ✅ Create direct cash entry for any member (bypasses approval queue)
- ✅ View full global ledger & print/PDF report
- ✅ Toggle public due warning modal on/off
- ✅ Grant due exemption (max 6 months)

#### Vice President / Admin (রুবেল মোল্লা) — Extra Access
- ✅ Issue & manage Royal Proclamations / Announcements (Priority 3)
- ✅ Add/Edit/Delete events and photo galleries
- ✅ Select which events appear in Masonry Gallery
- ✅ Mark events as highlighted for Highlights section
- ✅ Add/Edit/Delete investment projects
- ✅ Add/Edit/Delete somiti bylaws & rules

#### Cashier (সাইফুল ইসলাম) — Extra Access
- ✅ Issue & manage Royal Proclamations / Announcements (Priority 2)
- ✅ Review & approve/reject payment slips from members
- ✅ Adjust verified amount & months before approving
- ✅ Create direct cash entry for any member (bypasses approval queue)
- ✅ View full global ledger & print/PDF report
- ✅ Toggle public due warning modal on/off
- ✅ Grant due exemption (max 6 months)

#### President / Super Admin (সজিব মোল্লা) — Full Access
- ✅ All above permissions
- ✅ Issue & manage Royal Proclamations / Announcements (Priority 1 — Shows First)
- ✅ Grant due exemption (max 12 months, with mandatory explanation)
- ✅ Change monthly installment amount
- ✅ **Customize committee roles** — Assign any user as President, VP, Cashier, or Member
- ✅ **Edit website content** — Bilingual motto, footer text, vision description
- ✅ **Site Settings** — Upload logo, favicon, change organization name, contact details, bKash/Nagad numbers, bank info
- ✅ Set due date of month (e.g., day 15 — after which unpaid current month shows in alert)
- ✅ Add/Edit/Delete all members

---

## 📜 Royal Decrees & Announcements (রাজকীয় ফরমান ও নোটিশ)

### Ancient King Proclamation Design & Experience
- **Aesthetic**: Ancient aged parchment paper texture, double gold/bronze border, royal crimson wax seal badge (`👑` / `💼` / `🎖️`), and classical calligraphy header.
- **Royal Signoff**: Bottom right corner contains the royal signature seal with the author's name and official designation (e.g., `✍️ সজিব মোল্লা, সভাপতি (President)`).
- **Sequential Modal Stacking / Prioritization**:
  1. **১ম (Priority 1)**: President's Announcements (shows first on visit)
  2. **২য় (Priority 2)**: Cashier's Announcements (shows upon closing President's)
  3. **৩য় (Priority 3)**: Vice President's Announcements
  4. **৪র্থ (Priority 4)**: Due Alert Notice Modal (shows after all proclamations are closed)
- **Creator-Only Social Media Sharing**:
  - Only the **creator** of the announcement sees the direct social sharing toolbar (`Facebook`, `FB Messenger`, `WhatsApp`, and copyable formatted link).
  - Other users cannot broadcast unauthorized shares.


## 💰 Payment System

### Member Submission Flow
1. Member transfers via bKash/Nagad/Rocket/Bank/Cash
2. Member logs in and submits: amount + months count + TrxID + receipt screenshot
3. System auto-calculates LIFO (oldest unpaid months first)
4. Cashier reviews proof, adjusts if needed, approves
5. Approved entry adds to ledger, deducts from member's due

### Direct Cash Entry (Cashier/President)
- Cashier/President can enter payment on behalf of a member (e.g., they handed cash in person)
- Entry is instantly approved with "Cashier / President" as entry source
- Shows in ledger with 'cashier' or 'superadmin' badge

### Ledger Export
- Filter by date range and status
- Paginated: **12 records per page**
- Print-ready with official header (logo + org name)
- Signature blocks for Cashier and President at bottom
- Dynamic names from actual assigned roles

---

## ⚠️ Due Warning Modal Logic

The public due warning alert follows these rules:

1. **President must set the due day** (e.g., 15th of each month) in Site Settings
2. If a member has ≥1 month due AND the due day has passed for the current month → they appear in the alert
3. If a member's only due is the current month AND the due day has NOT passed → they do NOT appear
4. If a member has been granted exemption → they do NOT appear, even if technically overdue
5. If exemption covers more months than due (e.g., 3 months exempt for 1 month due) → future months also protected

---

## 🎖️ Role & Committee Management

The President can reassign any role at any time via Dashboard → "কমিটি পদবী পুনর্বিন্যাস":

- **সভাপতি (President)** → Full control (1 fixed, cannot be demoted from portal)
- **সহ-সভাপতি (Vice President)** → Events, Projects, Rules management
- **ক্যাশিয়ার (Cashier)** → Payment approvals, direct entry, exemptions, ledger
- **কার্যনির্বাহী সদস্য (Member)** → View-only ledger, submit own payments

---

## 🖼️ Site Customization (President Settings Panel)

Dashboard → "সমিতির পরিচয় ও লোগো" section:

| Field | Description |
|---|---|
| Logo | Upload new logo (replaces across navbar, 3D medallion, footer, login modal) |
| Favicon | Upload browser tab favicon |
| Somiti Name (Bengali) | e.g., "উপলব্ধি সমবায় সমিতি" |
| Somiti Name (English) | e.g., "USS Cooperative" |
| Motto (Bengali + English) | Shown in hero section |
| Est. Year | Founded year shown in hero stats |
| Due Day of Month | e.g., "15" — after this day, current month appears in due alert |
| Contact Phone | Shown in footer |
| Contact Email | Shown in footer |
| Contact Address | Shown in footer |
| bKash/Nagad/Rocket/Bank | Payment instruction numbers |
| Footer Description (BN+EN) | Bilingual footer paragraph |

---

## 📁 File Structure

```
content/
├── collections/
│   ├── events/        — Event entries (with gallery_images, is_highlighted, is_masonry_selected)
│   ├── payments/      — Payment records (LIFO system, entry_type, reviewed_by)
│   ├── projects/      — Investment projects
│   └── rules/         — Somiti bylaws
└── globals/
    └── somiti_settings.yaml — Global settings (installment amount, logo, contact info, due day)

users/
├── president@upolobdi.org.yaml
├── vp@upolobdi.org.yaml
├── cashier@upolobdi.org.yaml
├── kawser@upolobdi.org.yaml
├── doulot@upolobdi.org.yaml
└── ferdous@upolobdi.org.yaml

public/assets/images/
├── user-logo.jpg      — Official USS emblem
└── avatars/           — Member profile photos

public/uploads/
├── payments/          — Payment receipt screenshots
├── events/            — Event photos
├── projects/          — Project images
└── settings/          — Uploaded logos/favicons
```

---

## 🔒 Security Notes

- All financial totals on public landing page are blurred using server-rendered CSS with no actual value exposed in source HTML
- Amount values in public hero/overview are replaced with `•••••••• BDT` server-side
- Statamic CP (`/cp`) is accessible only to the Super Admin
- All payment, approval, and role-change actions are protected by middleware role checks
- Passwords are hashed using bcrypt

---

*Built with ❤️ for উপলব্ধি সমবায় সমিতি (USS) — সত্যের পথে স্বপ্নের অভিযান*
