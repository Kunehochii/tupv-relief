# RELIEF - Implementation Change Plan (January 30, 2026)

> Step-by-step implementation guide for the January 2026 client updates

---

## Overview

This document outlines the database migrations, model changes, controller updates, and view modifications required to implement the new RELIEF features based on client feedback in CHANGES.md.

**Important Notes:**
- Database can be reset (test data only)
- Money pledges remain manual (no QRPH integration)
- Pledge notifications are system-only (no email) to reduce costs

---

## Table of Contents

1. [Database Changes](#1-database-changes)
2. [Model Updates](#2-model-updates)
3. [Controller Changes](#3-controller-changes)
4. [View Updates](#4-view-updates)
5. [Storage Configuration](#5-storage-configuration)
6. [Testing Checklist](#6-testing-checklist)

---

## 1. Database Changes

### 1.1 Create Mother Formula Reference Table

Create migration: `create_relief_pack_items_table.php`

```php
Schema::create('relief_pack_items', function (Blueprint $table) {
    $table->id();
    $table->string('pack_type'); // food, kitchen, hygiene, sleeping, clothing
    $table->string('item_name');
    $table->decimal('quantity_per_family', 10, 2);
    $table->string('unit'); // kg, pcs, L, sachets, tins, packs, pairs, etc.
    $table->timestamps();
});
```

**Seed data based on Mother Formula:**

| Pack Type | Item Name | Qty/Family | Unit |
|-----------|-----------|------------|------|
| food | Rice | 6 | kg |
| food | Coffee Sachets | 5 | sachets |
| food | Powdered Cereal Drink Sachets | 5 | sachets |
| food | Corned Beef | 4 | tins |
| food | Tuna | 4 | tins |
| food | Sardines | 2 | tins |
| kitchen | Spoon | 5 | pcs |
| kitchen | Fork | 5 | pcs |
| kitchen | Drinking Glass | 5 | pcs |
| kitchen | Plate | 5 | pcs |
| kitchen | Frying Pan | 1 | pcs |
| kitchen | Cooking Pan | 1 | pcs |
| kitchen | Ladle | 1 | pcs |
| hygiene | Toothbrush | 5 | pcs |
| hygiene | Toothpaste | 2 | pcs |
| hygiene | Shampoo Bottle | 1 | bottle |
| hygiene | Bath Bar Soap | 4 | pcs |
| hygiene | Laundry Bar Soap | 2000 | grams |
| hygiene | Sanitary Napkin | 4 | packs |
| hygiene | Comb | 1 | pcs |
| hygiene | Disposable Shaving Razor | 1 | pcs |
| hygiene | Nail Cutter | 1 | pcs |
| hygiene | Bathroom Dipper | 1 | pcs |
| hygiene | 20L Plastic Bucket with Cover | 1 | pcs |
| sleeping | Blanket | 1 | pcs |
| sleeping | Plastic Mat | 1 | pcs |
| sleeping | Mosquito Net | 1 | pcs |
| sleeping | Malong (Wrap Cloth) | 1 | pcs |
| clothing | Bath Towel | 5 | pcs |
| clothing | Ladies Panty | 2 | pcs |
| clothing | Girls Panty | 3 | pcs |
| clothing | Mens Brief | 2 | pcs |
| clothing | Boys Brief | 3 | pcs |
| clothing | Sando Bra Adult | 2 | pcs |
| clothing | Sando Bra Girls | 3 | pcs |
| clothing | Adults T-Shirt | 4 | pcs |
| clothing | Childrens T-Shirt | 6 | pcs |
| clothing | Adults Short Pants | 4 | pcs |
| clothing | Childrens Short | 6 | pcs |
| clothing | Adults Slippers | 2 | pairs |
| clothing | Childrens Slippers | 3 | pairs |

---

### 1.2 Update Drives Table

Create migration: `update_drives_for_jan2026_changes.php`

```php
Schema::table('drives', function (Blueprint $table) {
    // Cover photo field
    $table->string('cover_photo')->nullable()->after('description');
    
    // Pack types needed (JSON array of pack types)
    $table->json('pack_types_needed')->nullable()->after('items_needed');
    
    // Families affected (for auto-calculation)
    $table->integer('families_affected')->nullable()->after('pack_types_needed');
    
    // Track pledged vs distributed amounts separately
    $table->decimal('pledged_amount', 12, 2)->default(0)->after('collected_amount');
    $table->decimal('distributed_amount', 12, 2)->default(0)->after('pledged_amount');
});

// Rename collected_amount to be clearer (optional, for clarity)
// Or keep collected_amount as legacy and use new fields
```

**Fields Explanation:**
- `cover_photo`: Path to 16:9 cover image (stored in `storage/app/public/drive-covers/`)
- `pack_types_needed`: JSON array like `["food", "kitchen", "hygiene"]`
- `families_affected`: Number input that auto-generates items_needed via mother formula
- `pledged_amount`: Sum of verified pledge quantities
- `distributed_amount`: Sum of distributed pledge quantities

---

### 1.3 Create Drive Items Table (Detailed Item Tracking)

Create migration: `create_drive_items_table.php`

```php
Schema::create('drive_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('drive_id')->constrained()->onDelete('cascade');
    $table->string('item_name');
    $table->decimal('quantity_needed', 12, 2);
    $table->decimal('quantity_pledged', 12, 2)->default(0);
    $table->decimal('quantity_distributed', 12, 2)->default(0);
    $table->string('unit');
    $table->string('pack_type')->nullable(); // Which pack this belongs to
    $table->boolean('is_custom')->default(false); // Admin manually added
    $table->timestamps();
    
    $table->index(['drive_id', 'item_name']);
});
```

**Purpose:** Replaces the JSON `items_needed` with a proper relational table for accurate tracking.

---

### 1.4 Redesign Pledges Table

Create migration: `update_pledges_for_item_tracking.php`

```php
// Remove old fields from pledges (items, quantity will move to pledge_items)
Schema::table('pledges', function (Blueprint $table) {
    $table->dropColumn(['items', 'quantity']);
    
    // Add type field for future extensibility
    $table->enum('pledge_type', ['in-kind', 'financial'])->default('in-kind')->after('reference_number');
    $table->decimal('financial_amount', 12, 2)->nullable()->after('pledge_type'); // For manual financial tracking
});
```

---

### 1.5 Create Pledge Items Table

Create migration: `create_pledge_items_table.php`

```php
Schema::create('pledge_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pledge_id')->constrained()->onDelete('cascade');
    $table->foreignId('drive_item_id')->nullable()->constrained()->onDelete('set null');
    $table->string('item_name'); // Denormalized for display
    $table->decimal('quantity', 12, 2);
    $table->string('unit');
    
    // Distribution tracking per item
    $table->decimal('quantity_distributed', 12, 2)->default(0);
    $table->timestamp('distributed_at')->nullable();
    $table->integer('families_helped')->nullable(); // Calculated based on mother formula
    
    $table->timestamps();
    
    $table->index('pledge_id');
});
```

**Purpose:** Each pledge can have multiple items, each tracked separately for distribution and impact calculation.

---

### 1.6 Create NGO Drive Support Table

Create migration: `create_ngo_drive_supports_table.php`

```php
Schema::create('ngo_drive_supports', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // NGO user
    $table->foreignId('drive_id')->constrained()->onDelete('cascade');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    $table->unique(['user_id', 'drive_id']); // One support per NGO per drive
});
```

**Purpose:** Tracks which NGOs have clicked "SUPPORT" for which drives. Donors can see this list when they want to donate money.

---

## 2. Model Updates

### 2.1 Create ReliefPackItem Model

**File:** `app/Models/ReliefPackItem.php`

```php
class ReliefPackItem extends Model
{
    protected $fillable = ['pack_type', 'item_name', 'quantity_per_family', 'unit'];
    
    const PACK_FOOD = 'food';
    const PACK_KITCHEN = 'kitchen';
    const PACK_HYGIENE = 'hygiene';
    const PACK_SLEEPING = 'sleeping';
    const PACK_CLOTHING = 'clothing';
    
    const PACK_TYPES = [
        self::PACK_FOOD => 'Food Pack',
        self::PACK_KITCHEN => 'Kitchen Kit',
        self::PACK_HYGIENE => 'Hygiene Kit',
        self::PACK_SLEEPING => 'Sleeping Kit',
        self::PACK_CLOTHING => 'Family Clothing Kit',
    ];
    
    public static function getItemsForPackTypes(array $packTypes): Collection
    {
        return self::whereIn('pack_type', $packTypes)->get();
    }
    
    public static function calculateFamiliesHelped(string $itemName, float $quantity): int
    {
        $item = self::where('item_name', $itemName)->first();
        if (!$item || $item->quantity_per_family <= 0) {
            return 0;
        }
        // Round up as per client request
        return (int) ceil($quantity / $item->quantity_per_family);
    }
}
```

---

### 2.2 Create DriveItem Model

**File:** `app/Models/DriveItem.php`

```php
class DriveItem extends Model
{
    protected $fillable = [
        'drive_id', 'item_name', 'quantity_needed', 'quantity_pledged',
        'quantity_distributed', 'unit', 'pack_type', 'is_custom'
    ];
    
    protected $casts = [
        'quantity_needed' => 'decimal:2',
        'quantity_pledged' => 'decimal:2',
        'quantity_distributed' => 'decimal:2',
        'is_custom' => 'boolean',
    ];
    
    public function drive()
    {
        return $this->belongsTo(Drive::class);
    }
    
    public function pledgeItems()
    {
        return $this->hasMany(PledgeItem::class);
    }
    
    public function getRemainingNeededAttribute(): float
    {
        return max(0, $this->quantity_needed - $this->quantity_pledged);
    }
    
    public function getProgressPercentageAttribute(): float
    {
        if ($this->quantity_needed <= 0) return 0;
        return min(100, round(($this->quantity_pledged / $this->quantity_needed) * 100, 2));
    }
}
```

---

### 2.3 Create PledgeItem Model

**File:** `app/Models/PledgeItem.php`

```php
class PledgeItem extends Model
{
    protected $fillable = [
        'pledge_id', 'drive_item_id', 'item_name', 'quantity', 'unit',
        'quantity_distributed', 'distributed_at', 'families_helped'
    ];
    
    protected $casts = [
        'quantity' => 'decimal:2',
        'quantity_distributed' => 'decimal:2',
        'distributed_at' => 'datetime',
    ];
    
    public function pledge()
    {
        return $this->belongsTo(Pledge::class);
    }
    
    public function driveItem()
    {
        return $this->belongsTo(DriveItem::class);
    }
    
    public function calculateFamiliesHelped(): int
    {
        return ReliefPackItem::calculateFamiliesHelped($this->item_name, $this->quantity_distributed);
    }
    
    public function isFullyDistributed(): bool
    {
        return $this->quantity_distributed >= $this->quantity;
    }
}
```

---

### 2.4 Create NgoDriveSupport Model

**File:** `app/Models/NgoDriveSupport.php`

```php
class NgoDriveSupport extends Model
{
    protected $fillable = ['user_id', 'drive_id', 'is_active'];
    
    protected $casts = ['is_active' => 'boolean'];
    
    public function ngo()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function drive()
    {
        return $this->belongsTo(Drive::class);
    }
}
```

---

### 2.5 Update Drive Model

**File:** `app/Models/Drive.php`

**Add relationships:**
```php
public function driveItems()
{
    return $this->hasMany(DriveItem::class);
}

public function ngoSupports()
{
    return $this->hasMany(NgoDriveSupport::class);
}

public function supportingNgos()
{
    return $this->belongsToMany(User::class, 'ngo_drive_supports')
                ->wherePivot('is_active', true)
                ->withTimestamps();
}
```

**Add to $fillable:**
```php
'cover_photo', 'pack_types_needed', 'families_affected', 'pledged_amount', 'distributed_amount'
```

**Add to casts:**
```php
'pack_types_needed' => 'array',
```

**Add accessors for 3-color progress bar:**
```php
public function getPledgedPercentageAttribute(): float
{
    if ($this->target_amount <= 0) return 0;
    return min(100, round(($this->pledged_amount / $this->target_amount) * 100, 2));
}

public function getDistributedPercentageAttribute(): float
{
    if ($this->target_amount <= 0) return 0;
    return min(100, round(($this->distributed_amount / $this->target_amount) * 100, 2));
}

public function getCoverPhotoUrlAttribute(): ?string
{
    if (!$this->cover_photo) return null;
    return asset('storage/' . $this->cover_photo);
}
```

**Add method to generate items from families affected:**
```php
public function generateItemsFromFamilies(): void
{
    if (!$this->families_affected || empty($this->pack_types_needed)) {
        return;
    }
    
    $packItems = ReliefPackItem::getItemsForPackTypes($this->pack_types_needed);
    
    foreach ($packItems as $packItem) {
        $this->driveItems()->updateOrCreate(
            ['item_name' => $packItem->item_name],
            [
                'quantity_needed' => $packItem->quantity_per_family * $this->families_affected,
                'unit' => $packItem->unit,
                'pack_type' => $packItem->pack_type,
                'is_custom' => false,
            ]
        );
    }
}
```

---

### 2.6 Update Pledge Model

**File:** `app/Models/Pledge.php`

**Add relationship:**
```php
public function pledgeItems()
{
    return $this->hasMany(PledgeItem::class);
}
```

**Add to $fillable:**
```php
'pledge_type', 'financial_amount'
```

**Remove from $fillable:**
```php
'items', 'quantity' // These move to pledge_items table
```

**Add helper methods:**
```php
public function getTotalQuantityAttribute(): float
{
    return $this->pledgeItems->sum('quantity');
}

public function getTotalDistributedAttribute(): float
{
    return $this->pledgeItems->sum('quantity_distributed');
}

public function getTotalFamiliesHelpedAttribute(): int
{
    return $this->pledgeItems->sum('families_helped') ?? 0;
}

public function isFullyDistributed(): bool
{
    return $this->pledgeItems->every(fn($item) => $item->isFullyDistributed());
}
```

---

### 2.7 Update User Model (for NGO Support)

**File:** `app/Models/User.php`

**Add relationship:**
```php
public function supportedDrives()
{
    return $this->belongsToMany(Drive::class, 'ngo_drive_supports')
                ->wherePivot('is_active', true)
                ->withTimestamps();
}

public function driveSupports()
{
    return $this->hasMany(NgoDriveSupport::class);
}
```

---

## 3. Controller Changes

### 3.1 Update Admin\DriveController

**File:** `app/Http/Controllers/Admin/DriveController.php`

**create() method updates:**
- Pass `ReliefPackItem::PACK_TYPES` to view for multi-select
- Pass existing `ReliefPackItem` list for custom item suggestions

**store() method updates:**
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'cover_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120|dimensions:ratio=16/9',
        'target_type' => 'required|in:financial,quantity',
        'target_amount' => 'required|numeric|min:0',
        'start_date' => 'nullable|date',
        'end_date' => 'required|date|after:start_date',
        'pack_types_needed' => 'nullable|array',
        'pack_types_needed.*' => 'in:food,kitchen,hygiene,sleeping,clothing',
        'families_affected' => 'nullable|integer|min:1',
        'custom_items' => 'nullable|array',
        'custom_items.*.name' => 'required_with:custom_items|string',
        'custom_items.*.quantity' => 'required_with:custom_items|numeric|min:0',
        'custom_items.*.unit' => 'required_with:custom_items|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'address' => 'nullable|string|max:500',
    ]);
    
    // Handle cover photo upload
    if ($request->hasFile('cover_photo')) {
        $path = $request->file('cover_photo')->store('drive-covers', 'public');
        $validated['cover_photo'] = $path;
    }
    
    $validated['created_by'] = auth()->id();
    
    $drive = Drive::create($validated);
    
    // Generate items from mother formula if families_affected provided
    if ($drive->families_affected && !empty($drive->pack_types_needed)) {
        $drive->generateItemsFromFamilies();
    }
    
    // Add custom items
    if (!empty($validated['custom_items'])) {
        foreach ($validated['custom_items'] as $customItem) {
            $drive->driveItems()->create([
                'item_name' => $customItem['name'],
                'quantity_needed' => $customItem['quantity'],
                'unit' => $customItem['unit'],
                'is_custom' => true,
            ]);
        }
    }
    
    return redirect()->route('admin.drives.show', $drive)
                     ->with('success', 'Drive created successfully!');
}
```

**update() method:** Similar changes for editing drives

**Add recalculate method:**
```php
public function recalculateProgress(Drive $drive)
{
    // Recalculate pledged_amount from verified pledges
    $pledgedTotal = $drive->pledges()
        ->where('status', Pledge::STATUS_VERIFIED)
        ->orWhere('status', Pledge::STATUS_DISTRIBUTED)
        ->with('pledgeItems')
        ->get()
        ->flatMap->pledgeItems
        ->sum('quantity');
    
    // Recalculate distributed_amount
    $distributedTotal = $drive->pledges()
        ->where('status', Pledge::STATUS_DISTRIBUTED)
        ->with('pledgeItems')
        ->get()
        ->flatMap->pledgeItems
        ->sum('quantity_distributed');
    
    $drive->update([
        'pledged_amount' => $pledgedTotal,
        'distributed_amount' => $distributedTotal,
    ]);
    
    // Update individual drive items too
    foreach ($drive->driveItems as $driveItem) {
        $pledged = PledgeItem::where('drive_item_id', $driveItem->id)
            ->whereHas('pledge', fn($q) => $q->whereIn('status', ['verified', 'distributed']))
            ->sum('quantity');
        
        $distributed = PledgeItem::where('drive_item_id', $driveItem->id)
            ->whereHas('pledge', fn($q) => $q->where('status', 'distributed'))
            ->sum('quantity_distributed');
        
        $driveItem->update([
            'quantity_pledged' => $pledged,
            'quantity_distributed' => $distributed,
        ]);
    }
}
```

---

### 3.2 Update Admin\PledgeController

**File:** `app/Http/Controllers/Admin/PledgeController.php`

**verify() method updates:**
- When verifying, update drive's `pledged_amount`
- Update relevant `drive_items.quantity_pledged`

**distribute() method updates:**
- Accept per-item distribution quantities
- Calculate families_helped per item using mother formula
- Send in-app notification per item (NO EMAIL)
- Update drive's `distributed_amount`

```php
public function distribute(Request $request, Pledge $pledge)
{
    $validated = $request->validate([
        'items' => 'required|array',
        'items.*.pledge_item_id' => 'required|exists:pledge_items,id',
        'items.*.quantity_distributed' => 'required|numeric|min:0',
        'admin_feedback' => 'nullable|string',
    ]);
    
    DB::transaction(function () use ($validated, $pledge) {
        $totalFamiliesHelped = 0;
        
        foreach ($validated['items'] as $itemData) {
            $pledgeItem = PledgeItem::find($itemData['pledge_item_id']);
            $qtyDistributed = min($itemData['quantity_distributed'], $pledgeItem->quantity);
            
            $familiesHelped = $pledgeItem->calculateFamiliesHelped();
            $totalFamiliesHelped += $familiesHelped;
            
            $pledgeItem->update([
                'quantity_distributed' => $qtyDistributed,
                'distributed_at' => now(),
                'families_helped' => $familiesHelped,
            ]);
            
            // Update drive_item totals
            if ($pledgeItem->drive_item_id) {
                $pledgeItem->driveItem->increment('quantity_distributed', $qtyDistributed);
            }
            
            // Send per-item notification (SYSTEM ONLY, NO EMAIL)
            $this->notificationService->notifyItemDistributed($pledge->user, $pledgeItem);
        }
        
        $pledge->update([
            'status' => Pledge::STATUS_DISTRIBUTED,
            'distributed_at' => now(),
            'families_helped' => $totalFamiliesHelped,
            'admin_feedback' => $validated['admin_feedback'] ?? null,
        ]);
        
        // Update drive totals
        $pledge->drive->increment('distributed_amount', 
            collect($validated['items'])->sum('quantity_distributed'));
    });
    
    return redirect()->route('admin.pledges.show', $pledge)
                     ->with('success', 'Pledge marked as distributed!');
}
```

---

### 3.3 Update Donor\PledgeController

**File:** `app/Http/Controllers/Donor/PledgeController.php`

**create() method:**
- Load `drive.driveItems` to show available items
- Donors see progress bar only, not exact quantities

**store() method:**
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'drive_id' => 'required|exists:drives,id',
        'pledge_type' => 'required|in:in-kind,financial',
        'items' => 'required_if:pledge_type,in-kind|array|min:1',
        'items.*.drive_item_id' => 'required|exists:drive_items,id',
        'items.*.quantity' => 'required|numeric|min:0.01',
        'financial_amount' => 'required_if:pledge_type,financial|numeric|min:0',
        'contact_number' => 'nullable|string|max:20',
        'notes' => 'nullable|string|max:1000',
    ]);
    
    DB::transaction(function () use ($validated) {
        $pledge = Pledge::create([
            'user_id' => auth()->id(),
            'drive_id' => $validated['drive_id'],
            'pledge_type' => $validated['pledge_type'],
            'financial_amount' => $validated['financial_amount'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => Pledge::STATUS_PENDING,
        ]);
        
        if ($validated['pledge_type'] === 'in-kind' && !empty($validated['items'])) {
            foreach ($validated['items'] as $item) {
                $driveItem = DriveItem::find($item['drive_item_id']);
                
                $pledge->pledgeItems()->create([
                    'drive_item_id' => $driveItem->id,
                    'item_name' => $driveItem->item_name,
                    'quantity' => $item['quantity'],
                    'unit' => $driveItem->unit,
                ]);
            }
        }
        
        // System notification only (NO EMAIL for pledge acknowledgement)
        $this->notificationService->notifyPledgeCreated($pledge, false);
    });
    
    return redirect()->route('donor.pledges.show', $pledge)
                     ->with('success', 'Pledge submitted! Reference: ' . $pledge->reference_number);
}
```

---

### 3.4 Create Ngo\DriveSupportController

**File:** `app/Http/Controllers/Ngo/DriveSupportController.php`

```php
class DriveSupportController extends Controller
{
    public function toggle(Request $request, Drive $drive)
    {
        $ngo = auth()->user();
        
        $support = NgoDriveSupport::where('user_id', $ngo->id)
                                   ->where('drive_id', $drive->id)
                                   ->first();
        
        if ($support) {
            $support->update(['is_active' => !$support->is_active]);
            $message = $support->is_active ? 'You are now supporting this drive!' : 'Support removed.';
        } else {
            NgoDriveSupport::create([
                'user_id' => $ngo->id,
                'drive_id' => $drive->id,
                'is_active' => true,
            ]);
            $message = 'You are now supporting this drive!';
        }
        
        return back()->with('success', $message);
    }
    
    public function mySupportedDrives()
    {
        $drives = auth()->user()->supportedDrives()->with('driveItems')->paginate(10);
        return view('ngo.supported-drives', compact('drives'));
    }
}
```

---

### 3.5 Update Donor\DashboardController

**File:** `app/Http/Controllers/Donor/DashboardController.php`

- When showing drives, do NOT expose exact item quantities
- Only expose progress bar percentages

---

### 3.6 Update Ngo\DashboardController

**File:** `app/Http/Controllers/Ngo/DashboardController.php`

- When showing drives, INCLUDE exact item quantities via `driveItems` relationship
- Show "SUPPORT" button for each drive

---

## 4. View Updates

### 4.1 Admin Drive Create/Edit Views

**File:** `resources/views/admin/drives/create.blade.php`

**Add:**
1. Cover photo upload field (with 16:9 preview)
2. Pack types multi-select checkboxes
3. Families affected input (optional)
4. Dynamic custom items adder (like Google Forms)

```html
<!-- Cover Photo -->
<div class="mb-3">
    <label for="cover_photo" class="form-label">Cover Photo (16:9 ratio) *</label>
    <input type="file" class="form-control" id="cover_photo" name="cover_photo" 
           accept="image/jpeg,image/png,image/jpg,image/webp" required>
    <small class="text-muted">Recommended: 1920x1080 or similar 16:9 aspect ratio</small>
    <div id="cover_preview" class="mt-2" style="display:none;">
        <img src="" alt="Preview" style="max-width: 100%; aspect-ratio: 16/9; object-fit: cover;">
    </div>
</div>

<!-- Pack Types Multi-Select -->
<div class="mb-3">
    <label class="form-label">Pack Types Needed</label>
    <div class="row">
        @foreach(\App\Models\ReliefPackItem::PACK_TYPES as $key => $label)
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" 
                           name="pack_types_needed[]" value="{{ $key }}" id="pack_{{ $key }}">
                    <label class="form-check-label" for="pack_{{ $key }}">{{ $label }}</label>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Families Affected -->
<div class="mb-3">
    <label for="families_affected" class="form-label">Number of Families Affected</label>
    <input type="number" class="form-control" id="families_affected" 
           name="families_affected" min="1" placeholder="Auto-calculates items from mother formula">
    <small class="text-muted">If set, items will be auto-generated based on selected pack types</small>
</div>

<!-- Custom Items Section -->
<div class="mb-3">
    <label class="form-label">Additional Custom Items</label>
    <div id="custom_items_container"></div>
    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addCustomItem()">
        <i class="bi bi-plus"></i> Add Item
    </button>
</div>
```

**JavaScript for custom items:**
```javascript
let itemIndex = 0;
function addCustomItem() {
    const container = document.getElementById('custom_items_container');
    const html = `
        <div class="row mb-2 custom-item" data-index="${itemIndex}">
            <div class="col-md-5">
                <input type="text" class="form-control" name="custom_items[${itemIndex}][name]" 
                       placeholder="Item name" required>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" name="custom_items[${itemIndex}][quantity]" 
                       placeholder="Quantity" min="0" step="0.01" required>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="custom_items[${itemIndex}][unit]" 
                       placeholder="Unit (kg, pcs)" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger" onclick="removeItem(${itemIndex})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    itemIndex++;
}

function removeItem(index) {
    document.querySelector(`.custom-item[data-index="${index}"]`).remove();
}
```

---

### 4.2 Update Progress Bar Component (All Views)

**Create partial:** `resources/views/partials/progress-bar-3color.blade.php`

```html
@props(['drive', 'showLegend' => true])

@php
    $neededPct = 100;
    $pledgedPct = $drive->pledged_percentage ?? 0;
    $distributedPct = $drive->distributed_percentage ?? 0;
@endphp

<div class="progress-stacked" style="height: 10px;">
    {{-- Distributed (green) --}}
    <div class="progress" role="progressbar" style="width: {{ $distributedPct }}%">
        <div class="progress-bar bg-success"></div>
    </div>
    {{-- Pledged but not distributed (blue) --}}
    <div class="progress" role="progressbar" style="width: {{ max(0, $pledgedPct - $distributedPct) }}%">
        <div class="progress-bar bg-primary"></div>
    </div>
    {{-- Remaining needed (gray/empty) - handled by background --}}
</div>

@if($showLegend)
<div class="d-flex justify-content-between mt-1">
    <small><span class="badge bg-success">●</span> Distributed: {{ $distributedPct }}%</small>
    <small><span class="badge bg-primary">●</span> Pledged: {{ $pledgedPct }}%</small>
    <small><span class="badge bg-secondary">●</span> Needed: {{ 100 - $pledgedPct }}%</small>
</div>
@endif
```

**Update all views using progress bars:**
- `resources/views/donor/dashboard.blade.php`
- `resources/views/ngo/dashboard.blade.php`
- `resources/views/public/drive-preview.blade.php`
- `resources/views/admin/drives/index.blade.php`
- `resources/views/admin/drives/show.blade.php`
- `resources/views/donor/map.blade.php`
- `resources/views/ngo/map.blade.php`
- `resources/views/welcome.blade.php`

---

### 4.3 Update Donor Pledge Create View

**File:** `resources/views/donor/pledges/create.blade.php`

- Show item list with progress bars (NOT exact quantities)
- Allow selecting items and entering pledge quantities
- Do not show `quantity_needed` values to donors

```html
@foreach($drive->driveItems as $item)
<div class="card mb-2">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $item->item_name }}</strong>
                <span class="text-muted">({{ $item->unit }})</span>
            </div>
            <div class="progress" style="width: 100px; height: 8px;">
                <div class="progress-bar bg-success" style="width: {{ $item->progress_percentage }}%"></div>
            </div>
        </div>
        <div class="mt-2">
            <label class="form-label small">Your pledge quantity:</label>
            <input type="number" class="form-control form-control-sm" 
                   name="items[{{ $loop->index }}][quantity]" min="0" step="0.01" value="0">
            <input type="hidden" name="items[{{ $loop->index }}][drive_item_id]" value="{{ $item->id }}">
        </div>
    </div>
</div>
@endforeach
```

---

### 4.4 Update NGO Dashboard View

**File:** `resources/views/ngo/dashboard.blade.php`

- Show exact item quantities needed
- Add "SUPPORT" button for each drive

```html
@foreach($activeDrives as $drive)
<div class="card mb-3">
    @if($drive->cover_photo)
        <img src="{{ $drive->cover_photo_url }}" class="card-img-top" style="aspect-ratio: 16/9; object-fit: cover;">
    @endif
    <div class="card-body">
        <h5>{{ $drive->name }}</h5>
        
        <!-- 3-Color Progress Bar -->
        @include('partials.progress-bar-3color', ['drive' => $drive])
        
        <!-- Exact Items Needed (NGO Only) -->
        <h6 class="mt-3">Items Needed:</h6>
        <ul class="list-group list-group-flush">
            @foreach($drive->driveItems as $item)
            <li class="list-group-item d-flex justify-content-between">
                <span>{{ $item->item_name }}</span>
                <span>
                    {{ number_format($item->quantity_pledged) }} / {{ number_format($item->quantity_needed) }} {{ $item->unit }}
                    <small class="text-muted">({{ number_format($item->quantity_distributed) }} distributed)</small>
                </span>
            </li>
            @endforeach
        </ul>
        
        <!-- Action Buttons -->
        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('ngo.pledges.create', ['drive_id' => $drive->id]) }}" class="btn btn-primary">
                <i class="bi bi-heart me-1"></i> Pledge
            </a>
            <form action="{{ route('ngo.drives.support', $drive) }}" method="POST" class="d-inline">
                @csrf
                @php $isSupporting = $drive->supportingNgos->contains(auth()->id()); @endphp
                <button type="submit" class="btn {{ $isSupporting ? 'btn-success' : 'btn-outline-success' }}">
                    <i class="bi bi-hand-thumbs-up me-1"></i>
                    {{ $isSupporting ? 'Supporting' : 'Support' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endforeach
```

---

### 4.5 Update Admin Pledge Distribution View

**File:** `resources/views/admin/pledges/distribute.blade.php` (new or update show)

- Show each pledge item
- Allow entering distributed quantity per item
- Auto-calculate families helped preview

```html
<form action="{{ route('admin.pledges.distribute', $pledge) }}" method="POST">
    @csrf
    @method('PATCH')
    
    <h5>Items to Distribute</h5>
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Pledged</th>
                <th>Distribute</th>
                <th>Est. Families Helped</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pledge->pledgeItems as $item)
            <tr>
                <td>{{ $item->item_name }} ({{ $item->unit }})</td>
                <td>{{ $item->quantity }}</td>
                <td>
                    <input type="hidden" name="items[{{ $loop->index }}][pledge_item_id]" value="{{ $item->id }}">
                    <input type="number" class="form-control distribution-qty" 
                           name="items[{{ $loop->index }}][quantity_distributed]"
                           value="{{ $item->quantity }}" min="0" max="{{ $item->quantity }}" step="0.01"
                           data-formula="{{ $item->driveItem?->quantity_per_family ?? 1 }}">
                </td>
                <td class="families-helped">-</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="mb-3">
        <label for="admin_feedback" class="form-label">Feedback / Notes</label>
        <textarea class="form-control" id="admin_feedback" name="admin_feedback" rows="3"></textarea>
    </div>
    
    <button type="submit" class="btn btn-success">
        <i class="bi bi-check-circle me-1"></i> Mark as Distributed
    </button>
</form>
```

---

### 4.6 Add Cover Photo Display to Drive Cards

Update all drive card displays to show cover photo:

```html
@if($drive->cover_photo)
    <img src="{{ $drive->cover_photo_url }}" class="card-img-top" 
         style="aspect-ratio: 16/9; object-fit: cover;" alt="{{ $drive->name }}">
@else
    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
         style="aspect-ratio: 16/9;">
        <i class="bi bi-image fs-1 text-white"></i>
    </div>
@endif
```

---

## 5. Storage Configuration

### 5.1 Create Storage Link

Run after deployment:
```bash
php artisan storage:link
```

This creates `public/storage` → `storage/app/public`

### 5.2 Storage Directories

Create directories:
- `storage/app/public/drive-covers/` - Drive cover photos
- `storage/app/public/certificates/` - NGO certificates (existing)

### 5.3 Update .gitignore

Ensure storage files are ignored but directories exist:
```gitignore
/storage/app/public/drive-covers/*
!/storage/app/public/drive-covers/.gitkeep
```

---

## 6. Route Updates

### 6.1 Add New Routes

**File:** `routes/web.php`

```php
// NGO Drive Support
Route::middleware(['auth', 'ngo', 'verified.ngo'])->prefix('ngo')->name('ngo.')->group(function () {
    Route::post('/drives/{drive}/support', [Ngo\DriveSupportController::class, 'toggle'])
         ->name('drives.support');
    Route::get('/supported-drives', [Ngo\DriveSupportController::class, 'mySupportedDrives'])
         ->name('supported-drives');
});

// Admin drive items recalculation
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/drives/{drive}/recalculate', [Admin\DriveController::class, 'recalculateProgress'])
         ->name('drives.recalculate');
});
```

---

## 7. NotificationService Updates

**File:** `app/Services/NotificationService.php`

### 7.1 Add Item Distribution Notification

```php
/**
 * Notify user about item distribution (SYSTEM ONLY, NO EMAIL)
 */
public function notifyItemDistributed(User $user, PledgeItem $item): void
{
    $message = "Your donation of {$item->quantity} {$item->unit} of {$item->item_name} " .
               "has helped {$item->families_helped} families!";
    
    // Create notification WITHOUT sending email
    Notification::create([
        'user_id' => $user->id,
        'type' => Notification::TYPE_PLEDGE_DISTRIBUTED,
        'title' => 'Donation Distributed!',
        'message' => $message,
        'link' => route('donor.pledges.show', $item->pledge_id),
    ]);
    
    // NO email for cost reduction
}
```

### 7.2 Update notifyPledgeCreated

```php
/**
 * @param bool $sendEmail - Set to false to skip email
 */
public function notifyPledgeCreated(Pledge $pledge, bool $sendEmail = false): void
{
    Notification::create([
        'user_id' => $pledge->user_id,
        'type' => Notification::TYPE_PLEDGE_ACKNOWLEDGED,
        'title' => 'Pledge Received',
        'message' => "Your pledge (Ref: {$pledge->reference_number}) has been received and is pending verification.",
        'link' => route('donor.pledges.show', $pledge),
    ]);
    
    // Only send email if explicitly requested
    if ($sendEmail) {
        // ... email logic
    }
}
```

---

## 8. Seeder Updates

### 8.1 Create ReliefPackItemSeeder

**File:** `database/seeders/ReliefPackItemSeeder.php`

Seed all items from the Mother Formula table in section 1.1.

### 8.2 Update DatabaseSeeder

```php
public function run(): void
{
    $this->call([
        AdminSeeder::class,
        ReliefPackItemSeeder::class,
    ]);
}
```

---

## 9. Migration Order

Run migrations in this order:

1. `create_relief_pack_items_table`
2. `update_drives_for_jan2026_changes`
3. `create_drive_items_table`
4. `update_pledges_for_item_tracking`
5. `create_pledge_items_table`
6. `create_ngo_drive_supports_table`

Then:
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

---

## 10. Testing Checklist

### Database
- [ ] All migrations run without errors
- [ ] ReliefPackItem seeder populates all mother formula items
- [ ] Drive can be created with cover photo
- [ ] Drive items auto-generated from families_affected

### Admin Features
- [ ] Can create drive with 16:9 cover photo
- [ ] Can select multiple pack types
- [ ] Can add custom items
- [ ] Items auto-calculate from families affected
- [ ] Can distribute pledge items individually
- [ ] Families helped calculated correctly

### Donor Features
- [ ] Cannot see exact item quantities
- [ ] Can see 3-color progress bar
- [ ] Can pledge to specific items
- [ ] Receives system notification on distribution

### NGO Features
- [ ] Can see exact item quantities
- [ ] Can click SUPPORT button
- [ ] Support status persists
- [ ] Can pledge same as donor

### Progress Bar
- [ ] Shows 3 colors correctly
- [ ] Updates when pledge verified
- [ ] Updates when pledge distributed

---

## Summary of Key Changes

| Area | Change |
|------|--------|
| Drives | Add cover_photo (16:9), pack_types_needed, families_affected, pledged_amount, distributed_amount |
| Items | New drive_items table replaces items_needed JSON |
| Pledges | New pledge_items table for per-item tracking |
| Progress | 3-color bar: Distributed (green), Pledged (blue), Needed (gray) |
| NGO | New SUPPORT feature, can see exact quantities |
| Donor | Cannot see exact quantities, only progress |
| Notifications | Item distribution notifications are system-only (no email) |
| Formula | Mother formula seeded for impact calculation |

---

*Document created: January 30, 2026*
*For implementation by AI coding agent*
