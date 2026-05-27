<?php

return [

    // ── Sidebar Navigation ────────────────────────────────
    'nav' => [
        'main'              => 'Main',
        'dashboard'         => 'Dashboard',
        'master_data'       => 'Database',
        'data_material'     => 'Material',
        'data_part'         => 'Part',
        'data_supplier'     => 'Supplier',
        'data_customer'     => 'Customer',
        'data_warehouse'    => 'Warehouse',
        'data_project'      => 'Projects',
        'purchasing'        => 'Procurement',
        'purchase_request'  => 'Purchase Request',
        'purchase_order'    => 'Purchase Order',
        'good_receipt'      => 'Goods Receipt',
        'receiving_report'  => 'GR Report',
        'good_issue'        => 'Goods Issue',
        'disbursal_report'  => 'GI Report',
        'inventory'         => 'Inventory',
        'goods_adjustment'  => 'Stock Opname',
        'inventory_stock'   => 'Stock Overview',
        'mutation_history'  => 'Transaction History',
        'work_order'        => 'Work Order',
        'quality_check'     => 'Quality Control',
        'administration'    => 'Administration',
        'account_mgmt'      => 'User Management',
        'logout'            => 'Sign Out',
    ],

    // ── Topbar ────────────────────────────────────────────
    'topbar' => [
        'toggle_menu'   => 'Toggle Menu',
        'toggle_theme'  => 'Toggle Theme',
        'language'      => 'Language',
    ],

    // ── Branding ──────────────────────────────────────────
    'brand' => [
        'subtitle' => 'SIM Inventory',
    ],

    // ── Days & Months ─────────────────────────────────────
    'days'   => ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
    'months' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],

    // ── Flash Messages ────────────────────────────────────
    'flash' => [
        'success' => 'Success',
        'error'   => 'An Error Occurred',
    ],

    // ── Common Buttons ────────────────────────────────────
    'btn' => [
        'save'         => 'Save',
        'save_changes' => 'Save Changes',
        'cancel'       => 'Cancel',
        'edit'         => 'Edit',
        'delete'       => 'Delete',
        'create'       => 'Create New',
        'back'         => 'Back',
        'search'       => 'Search',
        'export'       => 'Export',
        'print'        => 'Print',
        'approve'      => 'Approve',
        'reject'       => 'Reject',
        'confirm'      => 'Confirm',
        'detail'       => 'Detail',
        'add'          => 'Add',
        'add_item'     => 'Add Item',
        'deactivate'   => 'Deactivate',
        'activate'     => 'Activate',
        'filter'       => 'Filter',
        'reset'        => 'Reset',
    ],

    // ── Table / Common Status ─────────────────────────────
    'common' => [
        'additional_notes' => 'Additional Notes',
        'no'             => 'No.',
        'action'         => 'Action',
        'status'         => 'Status',
        'active'         => 'Active',
        'inactive'       => 'Inactive',
        'all_status'     => 'All Status',
        'date'           => 'Date',
        'description'    => 'Description',
        'total'          => 'Total',
        'quantity'       => 'Quantity',
        'qty'            => 'Qty',
        'unit'           => 'Unit',
        'price'          => 'Price',
        'name'           => 'Name',
        'code'           => 'Code',
        'category'       => 'Category',
        'notes'          => 'Notes',
        'created_at'     => 'Created At',
        'updated_at'     => 'Updated At',
        'no_data'        => 'No data available.',
        'loading'        => 'Loading...',
        'confirm_delete' => 'Are you sure you want to delete this?',
        'search_placeholder' => 'Search...',
        'warehouse'      => 'Warehouse',
        'type'           => 'Type',
        'supplier'       => 'Supplier',
        'stock'          => 'Stock',
        'time'           => 'Time',
        'item_name'      => 'Item Name',
        'item_code'      => 'Item Code',
        'total_item'     => 'Total Items',
        'po_number'      => 'PO No.',
        'po_date'        => 'PO Date',
        'source_warehouse' => 'Source Warehouse',
        'created_by'     => 'Created By',
    ],

    // ── Pagination ────────────────────────────────────────
    'pagination' => [
        'showing' => 'Showing :from–:to of :total :entity',
    ],

    // ── Dashboard ─────────────────────────────────────────
    'dashboard' => [
        'welcome'           => 'Welcome 👋',
        'subtitle'          => 'Inventory Management System Overview',
        'total_material'    => 'Total Materials',
        'material_in_today' => 'Materials In Today',
        'material_out_today'=> 'Materials Out Today',
        'low_stock'         => 'Low Stock Items',
        'empty_stock'       => 'Empty Stock',
        'good_issue_monthly'=> 'Good Issues This Month',
        'chart_title'       => 'Transactions Last 7 Days',
        'low_stock_alert'   => 'Low Stock Alert',
        'view_all'          => 'View All',
        'recent_mutation'   => 'Recent Transactions',
        'recent_good_issue' => 'Recent Good Issues',
        'all_stock_normal'  => 'All Stock Normal',
        'no_low_stock'      => 'No low-stock materials found.',
        'no_mutation'       => 'No Transactions Yet',
        'no_good_issue'     => 'No Good Issues Yet',
        'in'                => 'In',
        'out'               => 'Out',
        'qty_out'             => 'Qty Out',
        'gi_date'             => 'GI Issue Date',
        'default_purpose'   => 'Material Withdrawal',
    ],

    // ── Stock ─────────────────────────────────────────────
    'stock' => [
        'title'             => 'Inventory Stock',
        'subtitle'          => 'Stock list per warehouse',
        'search_placeholder'=> 'Search material / part name...',
        'min_stock'         => 'Min. Stock',
        'max_stock'         => 'Max. Stock',
        'current'           => 'Current Stock',
        'empty_title'       => 'No Stock Available',
        'empty_desc'        => 'Stock is created automatically when materials are received or issued.',
        'mutation_title'    => 'Stock Transaction History',
        'info'              => 'Stock Information',
        'status_empty'      => 'Empty',
        'status_low'        => 'Low',
        'status_normal'     => 'Normal',
        'empty'             => 'Empty',
        'low'               => 'Low',
        'normal'            => 'Normal',
        'in'                => 'In',
        'out'               => 'Out',
        'entity'            => 'stocks',
    ],

    // ── Mutasi / Transaction History ─────────────────────
    'mutasi' => [
        'title'             => 'Stock Transaction History',
        'subtitle'          => 'Record of all material in/out movements',
        'search_placeholder'=> 'Search item, document no., notes...',
        'all_types'         => 'All Types',
        'in'                => 'In (IN)',
        'out'               => 'Out (OUT)',
        'in_return'         => 'Return In (IN RETURN)',
        'time'              => 'Transaction Time',
        'item'              => 'Item (Material / Part)',
        'doc_ref'           => 'Document Reference',
        'balance'           => 'Balance',
        'balance_short'     => 'Balance',
        'ref_number'        => 'Ref. No.',
        'history'           => 'Transaction History',
        'empty_title'       => 'No Transaction History Yet',
        'empty_desc'        => 'Transaction records will appear when materials are received or issued.',
        'entity'            => 'transactions',
    ],

    // ── Supplier ─────────────────────────────────────────
    'supplier' => [
        'title'             => 'Supplier Data',
        'subtitle'          => 'Manage supplier / vendor list',
        'name'              => 'Supplier Name',
        'code'              => 'Supplier Code',
        'company_name'      => 'Company Name',
        'contact_person'    => 'Contact Person',
        'phone'             => 'Phone',
        'email'             => 'Email',
        'address'           => 'Address',
        'add'               => 'Add Supplier',
        'search_placeholder'=> 'Search name, phone, email...',
        'create_title'      => 'Add New Supplier',
        'create_subtitle'   => 'Fill in the form to register a new supplier / vendor',
        'edit_title'        => 'Edit Supplier',
        'edit_subtitle'     => 'Update supplier / vendor data',
        'detail_subtitle'   => 'Supplier / vendor detail information',
        'company_info'      => 'Company Information',
        'code_hint'         => 'Unique supplier code, max. 8 characters',
        'save_btn'          => 'Save Supplier',
        'empty_title'       => 'No Suppliers Yet',
        'empty_desc'        => 'Start by adding your first supplier',
        'po_history'        => 'Purchase Order History',
        'po_date'           => 'PO Date',
        'no_po'             => 'No PO Yet',
        'no_po_desc'        => 'This supplier has no purchase order history.',
        'entity'            => 'suppliers',
    ],

    // ── Material ──────────────────────────────────────────
    'material' => [
        'empty_title'   => 'No Materials Yet',
        'title'             => 'Material Data',
        'subtitle'          => 'Raw material database management',
        'name'              => 'Material Name',
        'code'              => 'Material Code',
        'spec'              => 'Specification',
        'dimension'         => 'Dimension / Length',
        'length'            => 'Material Length',
        'info'              => 'Material Information',
        'add'               => 'Add Material',
        'search_placeholder'=> 'Search name, code, supplier...',
        'create_title'      => 'Add New Material',
        'create_subtitle'   => 'Fill in the form to register a new raw material',
        'edit_title'        => 'Edit Material',
        'edit_subtitle'     => 'Update material data',
        'no_supplier'       => 'Supplier not set',
    ],

    // ── Warehouse ─────────────────────────────────────────
    'warehouse' => [
        'empty_title'   => 'No Warehouses Yet',
        'create_title'  => 'Add New Warehouse',
        'deactivate'    => 'Deactivate Warehouse',
        'activate'      => 'Activate Warehouse',
        'title'             => 'Warehouse',
        'subtitle'          => 'Manage material storage warehouse list',
        'name'              => 'Warehouse Name',
        'location'          => 'Location',
        'code'             => 'Warehouse Code',
        'info'             => 'Warehouse Information',
        'no_stock'             => 'This warehouse has no stock data yet.',
        'add'               => 'Add Warehouse',
        'search_placeholder'=> 'Search code, name, location...',
    ],

    // ── Customer ──────────────────────────────────────────
    'customer' => [
        'empty_title'   => 'No Customers Yet',
        'create_title'  => 'Add New Customer',
        'create_subtitle' => 'Fill in the form to register a new customer',
        'title'             => 'Customer Data',
        'subtitle'          => 'Manage customer / client list',
        'name'              => 'Customer Name',
        'add'               => 'Add Customer',
        'search_placeholder'=> 'Search name, phone, email...',
    ],

    // ── Part ──────────────────────────────────────────────
    'part' => [
        'empty_title'   => 'No Parts Yet',
        'create_title'  => 'Add New Part',
        'title'             => 'Part Data',
        'subtitle'          => 'Part (product) database management',
        'add'               => 'Add Part',
    ],

    // ── Project ───────────────────────────────────────────
    'project' => [
        'add'           => 'Add New Project',
        'create_title'  => 'Add New Project',
        'title'             => 'Project Data',
    ],

    // ── User ──────────────────────────────────────────────
    'user' => [
        'create_title'  => 'Add New User',
        'empty_title'   => 'No Users Yet',
        'title'             => 'User Data',
        'subtitle'          => 'Manage system user accounts',
        'add'               => 'Add User',
    ],

    // ── Good Issue ────────────────────────────────────────
    'good_issue' => [
        'empty_title'   => 'No Material Issues Yet',
        'title'             => 'Good Issue',
        'subtitle'          => 'Material issued from warehouse for production or other purposes',
        'add'               => 'Issue Material',
        'search_placeholder'=> 'Search GI No., Purpose / Notes...',
        'issue_date'        => 'Issue Date',
        'qty_out'             => 'Qty Out',
        'gi_date'             => 'GI Issue Date',
        'default_purpose'   => 'Material Withdrawal',
    ],

    // ── Good Receipt ──────────────────────────────────────
    'good_receipt' => [
        'empty_title'   => 'No Receipts Yet',
        'title'             => 'Good Receipt',
        'subtitle'          => 'Material received from supplier based on Purchase Order',
        'add'               => 'Receive New Material',
        'receive_date'      => 'Receive Date',
        'system_receiver'   => 'System Receiver',
        'pic_receiver'      => 'PIC Receiver',
    ],

    // ── Purchase Request ──────────────────────────────────
    'pr' => [
        'empty_title'   => 'No Purchase Requests Yet',
        'revert_draft'  => 'Revert to Draft',
        'title'             => 'Purchase Request',
        'subtitle'          => 'List of material purchase requests',
        'add'               => 'Create New PR',
        'search_placeholder'=> 'Search PR No...',
        'date'              => 'PR Date',
    ],

    // ── Return GI ─────────────────────────────────────────
    'return_gi' => [
        'subtitle'      => 'Return leftover/NG material from Work Order to warehouse stock',
        'empty_title'   => 'No Return Good Issues Yet',
        'detail_title'  => 'Material Returned to Warehouse (Stock Mutation IN)',
        'qty_return'    => 'Return Qty',
    ],

    // ── Withdrawal Card ───────────────────────────────────
    'withdrawal' => [
        'empty_title'   => 'No Withdrawal Cards Yet',
    ],

    // ── Purchase Order ────────────────────────────────────
    'po' => [
        'empty_title'   => 'No Purchase Orders Yet',
        'title'             => 'Purchase Order',
        'subtitle'          => 'List of purchase orders to suppliers',
        'add'               => 'Create New PO',
        'search_placeholder'=> 'Search PO No...',
    ],
];
