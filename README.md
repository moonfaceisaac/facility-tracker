# facility-tracker
a facility tracker
# Campus Facility Tracker
**Project Requirements & Technical Design**

**Project type:** Web-based CRUD application  
**Target users:** University administrative staff  
**Estimated development time:** 3 days  
**Status:** In development

---

## 1. Project Overview

### 1.1 Problem Statement

University staff need a simple way to manage campus equipment and track where equipment is located. Maintaining this information in spreadsheets can make searching, updating records, and monitoring equipment availability inconvenient.

Campus Facility Tracker provides a centralized web interface for managing equipment inventory and campus locations.

### 1.2 Project Objectives

- Provide CRUD functionality for equipment and campus locations.
- Display equipment statistics through a dashboard.
- Support searching, filtering, and pagination.
- Associate equipment with specific buildings and rooms.
- Provide a responsive and straightforward user interface.
- Demonstrate practical proficiency in PHP, Laravel, Vue.js, MySQL, and related development tools.

### 1.3 Project Scope

**Included in the MVP**

- Dashboard with inventory statistics.
- Equipment listing, creation, viewing, editing, and deletion.
- Campus location management.
- Search and filtering.
- Form validation.
- Database persistence.
- Responsive UI.
- Basic project documentation.

**Excluded from the MVP**

- Equipment booking and reservations.
- Maintenance ticket workflows.
- Notifications and email integration.
- Complex user roles and permissions.
- Mobile applications.
- Advanced reporting and analytics.
- Integration with existing university systems.

---

## 2. Technology Stack

| Technology | Purpose |
|---|---|
| PHP | Server-side programming language |
| Laravel | Routing, controllers, validation, database operations |
| Vue 3 | Reactive frontend components and pages |
| Inertia.js | Integration between Laravel and Vue |
| MySQL | Relational database |
| Eloquent ORM | Database queries and model relationships |
| Tailwind CSS | Responsive styling and UI layout |
| Vite | Frontend development and asset bundling |
| PhpStorm | Development environment |
| Git and GitHub | Version control and source code management |
| Jira | Task tracking and development workflow |
| Confluence | Requirements and technical documentation |

### Architecture

The application uses a monolithic Laravel architecture with Vue pages rendered through Inertia.js.

The request flow is:

1. A user navigates to a page or submits a form.
2. Laravel routes the request to the appropriate controller.
3. The controller retrieves or modifies data through Eloquent.
4. Laravel returns an Inertia response containing the page component and required data.
5. Vue renders or updates the interface.

MySQL stores all persistent application data.

A separate REST API and separately deployed frontend are not required for this MVP.

---

## 3. Functional Requirements

### FR-01: Dashboard

The dashboard must display:

- Total number of equipment records.
- Number of available equipment items.
- Number of equipment items under maintenance.
- Number of registered campus locations.
- Equipment distribution by category.

All statistics must be calculated from actual database records.

### FR-02: Equipment Management

Users must be able to:

- View all equipment in a paginated table.
- Add new equipment records.
- View equipment details.
- Edit existing records.
- Delete equipment records after confirmation.
- Search equipment by name or asset code.
- Filter equipment by category, status, and location.

Each equipment record must have a unique asset code.

### FR-03: Location Management

Users must be able to:

- View registered locations.
- Create a new location.
- Edit location information.
- View equipment assigned to a location.
- Delete a location when it has no associated equipment.

Locations with assigned equipment cannot be deleted until that equipment has been reassigned or removed.

### FR-04: Form Validation

The application must:

- Validate required fields.
- Enforce unique asset codes.
- Reject invalid location references.
- Display validation errors near the relevant fields.
- Preserve valid user input after validation failures.
- Display clear feedback after successful operations.

### FR-05: User Interface

The interface must include:

- A sidebar or simple navigation menu.
- Dashboard statistic cards.
- Data tables with action buttons.
- Create and edit forms.
- Status badges.
- Delete confirmation dialogs.
- Loading, empty, and error states where appropriate.
- Responsive layouts for desktop and mobile screens.

---

## 4. Data Model

The MVP requires two primary database tables: `locations` and `equipment`.

### 4.1 Locations Table

| Column | Type | Constraints |
|---|---|---|
| id | BIGINT | Primary key |
| building_name | VARCHAR(150) | Required |
| room_number | VARCHAR(50) | Required |
| description | TEXT | Nullable |
| created_at | TIMESTAMP | Laravel-managed |
| updated_at | TIMESTAMP | Laravel-managed |

### 4.2 Equipment Table

| Column | Type | Constraints |
|---|---|---|
| id | BIGINT | Primary key |
| asset_code | VARCHAR(50) | Required, unique |
| name | VARCHAR(150) | Required |
| category | VARCHAR(100) | Required |
| status | VARCHAR(30) | Required, default `available` |
| location_id | BIGINT | Foreign key to locations.id |
| description | TEXT | Nullable |
| purchase_date | DATE | Nullable |
| created_at | TIMESTAMP | Laravel-managed |
| updated_at | TIMESTAMP | Laravel-managed |

### 4.3 Equipment Categories

Use a predefined list in the frontend:

- Computer
- Projector
- Furniture
- Networking
- Laboratory Equipment
- Other

### 4.4 Equipment Statuses

Use the following values:

- `available`
- `in_use`
- `under_maintenance`

Display user-friendly labels in the UI rather than raw database values.

### 4.5 Relationships

- One location can contain many equipment records.
- Each equipment record belongs to one location.

Use a foreign key constraint for `equipment.location_id`. For this MVP, require each equipment record to have a location and prevent deletion of locations that still contain equipment.

**Implementation note:** Use Laravel migrations to define the schema and Eloquent models to manage the relationship. Add indexes where useful for asset-code lookups, status filters, and location queries.

---

## 5. Application Pages

### Page 1: Dashboard

**Route:** `/dashboard`

Components:

- Total equipment card.
- Available equipment card.
- Under-maintenance card.
- Total locations card.
- Equipment-by-category summary.
- Recently added equipment table (optional).

### Page 2: Equipment List

**Route:** `/equipment`

Components:

- Page heading and Add Equipment button.
- Search input.
- Category, status, and location filters.
- Paginated data table.
- View, Edit, and Delete actions.

### Page 3: Create Equipment

**Route:** `/equipment/create`

Fields:

- Asset code.
- Equipment name.
- Category.
- Status.
- Location.
- Description.
- Purchase date.

### Page 4: Edit Equipment

**Route:** `/equipment/{equipment}/edit`

Reuse the create form component with existing values prefilled.

### Page 5: Equipment Details

**Route:** `/equipment/{equipment}`

Display the equipment's complete information, including its assigned location and current status.

### Page 6: Location List

**Route:** `/locations`

Display building names, room numbers, descriptions, and equipment counts, with actions to create, view, edit, and delete locations.

### Page 7: Create and Edit Location

**Routes:**

- `/locations/create`
- `/locations/{location}/edit`

Fields:

- Building name.
- Room number.
- Description.

---

## 6. Backend Design

Organize backend code using Laravel's standard conventions.

### Models

- `Equipment`
- `Location`

### Controllers

- `DashboardController`
- `EquipmentController`
- `LocationController`

### Recommended Laravel Features

- Resource routes for equipment and locations.
- Eloquent relationships.
- Form Request classes for reusable validation, if time permits.
- Route model binding.
- Database migrations and seeders.
- Pagination through Eloquent queries.
- Flash messages for successful operations.

Keep business logic straightforward. Dedicated service classes are unnecessary unless the implementation develops more complicated logic.

### Suggested Route Structure

| Method | Route | Purpose |
|---|---|---|
| GET | `/dashboard` | Dashboard statistics |
| GET | `/equipment` | List and filter equipment |
| GET | `/equipment/create` | Display creation form |
| POST | `/equipment` | Store equipment |
| GET | `/equipment/{equipment}` | Show equipment |
| GET | `/equipment/{equipment}/edit` | Display edit form |
| PUT/PATCH | `/equipment/{equipment}` | Update equipment |
| DELETE | `/equipment/{equipment}` | Delete equipment |
| GET | `/locations` | List locations |
| POST | `/locations` | Create location |
| PUT/PATCH | `/locations/{location}` | Update location |
| DELETE | `/locations/{location}` | Delete location |

Use Laravel resource routes to generate the standard CRUD routes. The table is a reference, not a requirement to declare every route manually.

---

## 7. Frontend Design

### Suggested Vue Component Structure

- `AppLayout.vue`
- `Sidebar.vue`
- `StatCard.vue`
- `DataTable.vue`
- `SearchFilter.vue`
- `StatusBadge.vue`
- `ConfirmDialog.vue`
- `EquipmentForm.vue`
- `LocationForm.vue`

### Suggested Page Structure

- `Pages/Dashboard.vue`
- `Pages/Equipment/Index.vue`
- `Pages/Equipment/Create.vue`
- `Pages/Equipment/Edit.vue`
- `Pages/Equipment/Show.vue`
- `Pages/Locations/Index.vue`
- `Pages/Locations/Create.vue`
- `Pages/Locations/Edit.vue`

Reuse form components between create and edit pages where practical.

### UI Guidelines

- Use a consistent spacing scale and typography.
- Use cards for summary statistics.
- Use tables for administrative data.
- Use distinct but restrained colors for status badges.
- Place search and filters above the table.
- Keep destructive actions visually distinct.
- Avoid unnecessary animations and decorative components.
- Prioritize usability over elaborate visual design.

---

## 8. Validation and Error Handling

### Equipment Validation

- `asset_code`: required, string, unique, maximum 50 characters.
- `name`: required, string, maximum 150 characters.
- `category`: required, valid category.
- `status`: required, valid status.
- `location_id`: required, existing location ID.
- `description`: optional string.
- `purchase_date`: optional valid date.

When updating equipment, the uniqueness validation must ignore the current record's asset code.

### Location Validation

- `building_name`: required, string, maximum 150 characters.
- `room_number`: required, string, maximum 50 characters.
- `description`: optional string.

### Error Handling

- Return validation errors through Laravel's normal validation mechanisms.
- Show a friendly message when an operation fails.
- Prevent deletion of locations with associated equipment.
- Use database constraints as a second layer of data integrity.
- Never rely solely on frontend validation.

---

## 9. Testing Checklist

Before declaring the MVP complete, verify the following:

### Equipment

- [ ] Create equipment successfully.
- [ ] Edit existing equipment.
- [ ] View equipment details.
- [ ] Delete equipment after confirmation.
- [ ] Reject duplicate asset codes.
- [ ] Reject missing required fields.
- [ ] Search by equipment name and asset code.
- [ ] Filter by status, category, and location.
- [ ] Verify pagination.

### Locations

- [ ] Create and edit locations.
- [ ] Display associated equipment counts.
- [ ] Prevent deletion when equipment remains assigned.
- [ ] Verify equipment location relationships.

### Dashboard

- [ ] Statistic cards reflect database changes.
- [ ] Category summaries use actual records.
- [ ] Empty database states render correctly.

### General

- [ ] Forms work on desktop and mobile.
- [ ] Error messages are understandable.
- [ ] Deletion confirmation works.
- [ ] Database changes persist after page reload.
- [ ] Automated tests cover important validation and CRUD behavior.

---

## 10. Jira Work Breakdown

Create one Jira project and track the following tasks:

1. Set up Laravel, Vue, Inertia, and MySQL.
2. Create database migrations, models, and seeders.
3. Implement equipment CRUD.
4. Implement location CRUD.
5. Build the equipment listing, search, filters, and pagination.
6. Build dashboard statistics.
7. Polish the UI and handle errors.
8. Test the application and finish documentation.

Use a simple Kanban workflow: Backlog, To Do, In Progress, and Done.

For each task, define a short description and clear acceptance criteria. Update task statuses as work progresses.

---

## 11. Development Schedule

### Day 1: Foundation and Equipment CRUD

- Initialize the project.
- Configure MySQL.
- Create migrations and models.
- Implement equipment CRUD.
- Build the basic equipment table and forms.

### Day 2: Locations and Dashboard

- Implement location CRUD.
- Connect equipment to locations.
- Add search, filters, and pagination.
- Build dashboard cards and category summaries.

### Day 3: Testing and Delivery

- Test validation and CRUD operations.
- Fix UI and responsive-layout issues.
- Add seed data.
- Complete project documentation.
- Prepare a working demonstration.

If setup takes longer than expected, prioritize working CRUD and the dashboard. The location module can remain minimal rather than sacrificing the entire application to scope creep.

---

## 12. Definition of Done

The MVP is complete when:

- Equipment and location CRUD operations work.
- Records persist correctly in MySQL.
- Equipment can be assigned to locations.
- Dashboard statistics reflect actual database data.
- Search, filters, and pagination work.
- Forms provide appropriate validation feedback.
- The interface is responsive and usable.
- Core functionality has been tested.
- The repository includes setup instructions.
- Jira reflects the actual project status.

**Final deliverable:** a working Campus Facility Tracker web application built with Laravel, Vue 3, Inertia.js, MySQL, and Tailwind CSS, supported by a Git repository and concise technical documentation.
