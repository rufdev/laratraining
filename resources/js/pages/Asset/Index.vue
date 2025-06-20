<script setup lang="ts">
/* Import Components */
import ReusableAlertDialog from '@/components/entitycomponents/ReusableAlertDialog.vue';
import ReusableDropDownAction from '@/components/entitycomponents/ReusableDropDownAction.vue'; // Dropdown for row actions (edit/delete)
import ReusableTable from '@/components/entitycomponents/ReusableTable.vue'; // Table component for displaying data
import { AutoForm } from '@/components/ui/auto-form'; // AutoForm component for form handling
import { Button } from '@/components/ui/button'; // Button component
import { Checkbox } from '@/components/ui/checkbox'; // Checkbox component for row selection
import { Dialog, DialogDescription, DialogFooter, DialogHeader, DialogScrollContent, DialogTitle } from '@/components/ui/dialog'; // Dialog components for forms
import AppLayout from '@/layouts/AppLayout.vue'; // Layout component for the page
import { Head } from '@inertiajs/vue3'; // Head component for setting the page title

/* Import Utilities */
import { parseDate } from "@internationalized/date";
import { toTypedSchema } from '@vee-validate/zod'; // Utility for converting Zod schemas to Vee-Validate schemas
import axios from 'axios'; // HTTP client for API requests
import { ArrowUpDown, Plus } from 'lucide-vue-next'; // Icons for UI
import { useForm } from 'vee-validate'; // Form validation library
import { h, ref } from 'vue'; // Vue composition API utilities
import { toast } from 'vue-sonner'; // Toast notifications
import * as z from 'zod'; // Zod library for schema validation

/* Import Table Utilities */
import type { ColumnDef } from '@tanstack/vue-table'; // Type definitions for table columns

/* Import Types */
import { BreadcrumbItem } from '@/types'; // Type definition for breadcrumbs

/* Base Entity Configuration */
const baseentityurl = '/assets'; // API endpoint for the entity
const baseentityname = 'Asset'; // Name of the entity

/* Breadcrumbs */
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: baseentityname,
        href: baseentityurl,
    },
];


const props = defineProps({
    categories: {
        type: Object,
        required: true,
    },
    locations: {
        type: Object,
        required: true,
    },
    manufacturers: {
        type: Object,
        required: true,
    },
    users: {
        type: Object,
        required: true,
    },
});

/* Define Props */
export interface Asset {
    id: number;
    category: any;
    category_id: string;
    location: any;
    location_id: string;
    manufacturer: any;
    manufacturer_id: string;
    assigned_to: any;
    assigned_to_user_id: string;
    asset_tag: string;
    name: string;
    serial_number: string;
    model_name: string;
    purchase_date: string;
    purchase_price: any;
    status: string;
    notes: string;
}

const statusEnum = {
    Deployed: 'Deployed',
    InStorage: 'In Storage',
    Maintenance: 'Maintenance',
    Retired: 'Retired',
    Broken: 'Broken',
};

/* Define Table Columns */
const columns: ColumnDef<Asset>[] = [
    {
        id: 'select', // Column for row selection
        header: ({ table }) =>
            h(Checkbox, {
                modelValue: table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate'),
                'onUpdate:modelValue': (value) => table.toggleAllPageRowsSelected(!!value),
                ariaLabel: 'Select all',
            }),
        cell: ({ row }) =>
            h(Checkbox, {
                modelValue: row.getIsSelected(),
                'onUpdate:modelValue': (value) => row.toggleSelected(!!value),
                ariaLabel: 'Select row',
            }),
        enableSorting: false, // Disable sorting for this column
        enableHiding: false, // Disable hiding for this column
    },
    {
        accessorKey: 'name', // Column for category name
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'break-words whitespace-normal' }, row.getValue('name')),
    },
    {
        accessorKey: 'asset_tag',
        header: 'Asset Tag',
        cell: ({ row }) => h('div', { class: 'break-words whitespace-normal' }, row.getValue('asset_tag')),
    },
    {
        accessorKey: 'category.name',
        header: 'Category',
        cell: ({ row }) => h('div', { class: 'break-words whitespace-normal' }, row.original.category?.name || ''),
    },
    {
        accessorKey: 'location.name',
        header: 'Location',
        cell: ({ row }) => h('div', { class: 'break-words whitespace-normal' }, row.original.location?.name || ''),
    },
    {
        accessorKey: 'manufacturer.name',
        header: 'Manufacturer',
        cell: ({ row }) => h('div', { class: 'break-words whitespace-normal' }, row.original.manufacturer?.name || ''),
    },
    {
        accessorKey: 'assigned_to.name',
        header: 'Assigned To User',
        cell: ({ row }) => h('div', { class: 'break-words whitespace-normal' }, row.original.assigned_to?.name || ''),
    },
    {
        accessorKey: 'status',
        header: 'Status',
        cell: ({ row }) => {
            const status = row.original.status;
            let colorClass = '';

            // Define color classes based on statusEnum values
            const statusColors = {
                [statusEnum.Deployed]: 'text-green-500',
                [statusEnum.InStorage]: 'text-blue-500',
                [statusEnum.Maintenance]: 'text-yellow-500',
                [statusEnum.Retired]: 'text-gray-500',
                [statusEnum.Broken]: 'text-red-500',
            };

            colorClass = statusColors[status] || 'text-black';

            return h('div', { class: `break-words whitespace-normal ${colorClass}` }, status);
        },
    },
    {
        id: 'actions', // Column for row actions (edit/delete)
        enableHiding: false, // Disable hiding for this column
        cell: ({ row }) => {
            const rowitem = row.original;

            return h(
                'div',
                { class: 'relative' },
                h(ReusableDropDownAction, {
                    rowitem,
                    onEdit: handleEdit, // Edit handler
                    onDelete: openDeleteDialog, // Delete handler
                }),
            );
        },
    },
];

/* Dialog State */
const showDialogForm = ref(false); // State for showing/hiding the form dialog
const mode = ref('create'); // Mode for the form (create/edit)
const itemID = ref<number | null>(null); // ID of the item being edited

/* Form Schema and Configuration */
const schema = z.object({
    name: z
        .string({
            required_error: 'Name is required',
            invalid_type_error: 'Name must be a string',
        })
        .min(3, 'Name must be at least 3 characters long')
        .max(255, 'Name must not exceed 255 characters'),
    serial_number: z
        .string({
            required_error: 'Serial number is required',
            invalid_type_error: 'Serial number must be a string',
        })
        .max(255, 'Serial number must not exceed 255 characters'),
    model_name: z
        .string({
            invalid_type_error: 'Model name must be a string',
        })
        .max(255, 'Model name must not exceed 255 characters')
        .optional(),
    category_id: z.enum(
        props.categories.map((item: any) => item.name),
        {
            required_error: 'Category is required',
        },
    ),
    location_id: z
        .enum(
            props.locations.map((item: any) => item.name)
        ).nullable().optional(),
    manufacturer_id: z
        .enum(
            props.manufacturers.map((item: any) => item.name)
        ).nullable().optional(),
    assigned_to_user_id: z
        .enum(
            props.users.map((item: any) => item.name)
        ).nullable().optional(),
    asset_tag: z
        .string({
            required_error: 'Asset tag is required',
            invalid_type_error: 'Asset tag must be a string',
        })
        .max(255, 'Asset tag must not exceed 255 characters')
        .optional(),
    purchase_date: z.coerce.date().optional(),
    purchase_price: z.coerce
        .number({
            invalid_type_error: 'Purchase price must be a number',
        })
        .nonnegative('Purchase price must be a positive number')
        .optional(),
    status: z.enum(Object.values(statusEnum) as [string, ...string[]], {
        required_error: 'Status is required',
    }),
    notes: z
        .string({
            invalid_type_error: 'Notes must be a string',
        })
        .max(1000, 'Notes must not exceed 1000 characters')
        .nullable()
        .optional(),
});

const fieldconfig: any = {
    name: {
        label: 'Name',
        inputProps: {
            type: 'text',
            class: 'uppercase',
            placeholder: 'Enter asset name',
        },
        description: 'Name of the asset.',
    },
    serial_number: {
        label: 'Serial Number',
        inputProps: {
            type: 'text',
            class: 'uppercase',
            placeholder: 'Enter serial number',
        },
        description: 'Serial number of the asset.',
    },
    model_name: {
        label: 'Model Name',
        inputProps: {
            type: 'text',
            class: 'uppercase',
            placeholder: 'Enter model name',
        },
        description: 'Model name of the asset.',
    },
    category_id: {
        label: 'Select Category', // Custom label for the field
        component: 'select', // Tell AutoForm to render a <select> element
        inputProps: {
            placeholder: 'Choose a category...', // Placeholder for the select
        },
    },
    location_id: {
        label: 'Select Location', // Custom label for the field
        component: 'select', // Tell AutoForm to render a <select> element
        inputProps: {
            placeholder: 'Choose a location...', // Placeholder for the select
        },
    },
    manufacturer_id: {
        label: 'Select Manufacturer', // Custom label for the field
        component: 'select', // Tell AutoForm to render a <select> element
        inputProps: {
            placeholder: 'Choose a manufacturer...', // Placeholder for the select
        },
    },
    assigned_to_user_id: {
        label: 'Select Assigned User', // Custom label for the field
        component: 'select', // Tell AutoForm to render a <select> element
        inputProps: {
            placeholder: 'Choose a user...', // Placeholder for the select
        },
    },
    asset_tag: {
        label: 'Asset Tag',
        inputProps: {
            type: 'text',
            class: 'uppercase',
            placeholder: 'Enter asset tag',
        },
        description: 'Unique identifier for the asset.',
    },
    purchase_date: {
        label: 'Purchase Date',
        inputProps: {
            type: 'date',
        },
        description: 'Date when the asset was purchased.',
    },
    purchase_price: {
        label: 'Purchase Price',
        inputProps: {
            type: 'number',
            step: '0.01', // Allows 2 decimal places
            placeholder: 'Enter purchase price',
        },
        description: 'Price of the asset at the time of purchase.',
    },
    status: {
        label: 'Status',
        component: 'select', // Dropdown for selecting status
        description: 'Current status of the asset.',
    },
    notes: {
        label: 'Notes',
        component: 'textarea', // Textarea for notes
        inputProps: {
            class: 'uppercase',
            placeholder: 'Enter additional notes',
        },
        description: 'Additional information about the asset.',
    },
};

const form = useForm({
    validationSchema: toTypedSchema(schema), // Validation schema
    initialValues: {
        category_id: null,
        location_id: null,
        manufacturer_id: null,
        assigned_to_user_id: null,
        asset_tag: '',
        name: '',
        serial_number: '',
        model_name: '',
        purchase_date: null,
        purchase_price: 0,
        status: null,
        notes: '',
    },
});

/* Form Handlers */
const resetForm = () => {
    form.resetForm(); // Reset the form
    itemID.value = null; // Clear the item ID
};

const handleOpenDialogForm = () => {
    showDialogForm.value = true; // Show the form dialog
    mode.value = 'create'; // Set mode to create
};

const tableRef = ref<InstanceType<typeof ReusableTable> | null>(null); // Reference to the table component

const onSubmit = async (values: any) => {
    try {
        const mappedValues = {
            ...values,
            category_id: props.categories.find((category: any) => category.name === values.category_id)?.id || null,
            location_id: props.locations.find((location: any) => location.name === values.location_id)?.id || null,
            manufacturer_id: props.manufacturers.find((manufacturer: any) => manufacturer.name === values.manufacturer_id)?.id || null,
            assigned_to_user_id: props.users.find((user: any) => user.name === values.assigned_to_user_id)?.id || null,
        };

        if (mode.value === 'create') {
            await axios.post(`${baseentityurl}`, mappedValues); // Create a new category
            toast.success(`${baseentityname} created successfully.`);
        } else if (mode.value === 'edit') {
            await axios.put(`${baseentityurl}/${itemID.value}`, mappedValues); // Update an existing category
            toast.success(`${baseentityname} updated successfully.`);
        }

        resetForm(); // Reset the form
        await tableRef.value?.fetchRows(); // Refresh the table data
        showDialogForm.value = false; // Hide the form dialog
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            form.setErrors(error.response.data.errors); // Set validation errors
            toast.error('Validation failed. Please check your input.');
        } else {
            toast.error('An unexpected error occurred.');
        }
    }
};

/* Edit Handler */
const handleEdit = async (id: number) => {
    try {
        mode.value = 'edit'; // Set mode to edit
        itemID.value = id; // Set the item ID
        const response = await axios.get(`${baseentityurl}/${id}`); // Fetch the item data
        const data = response.data;
        const mappedData = {
            ...data,
            category_id: data.category?.name,
            location_id: data.location?.name,
            manufacturer_id: data.manufacturer?.name,
            assigned_to_user_id: data.assigned_to?.name,
            purchase_date: data.purchase_date ? parseDate(data.purchase_date.slice(0, 10)) : null, 
        };
        // const testdate = parseAbsolute(data.purchase_date, getLocalTimeZone())
        // console.log(df.format(testdate.toDate(getLocalTimeZone())))
        form.setValues(mappedData); // Populate the form with the item data
        showDialogForm.value = true; // Show the form dialog
    } catch (error) {
        console.log(`Error fetching ${baseentityname} data:`, error);
        toast.error(`Failed to fetch ${baseentityname} data.`);
    }
};

/* Delete Dialog State */
const showDeleteDialog = ref(false); // State for showing/hiding the delete confirmation dialog
const itemIDToDelete = ref<number | null>(null); // ID of the item to be deleted

const openDeleteDialog = (id: number) => {
    itemIDToDelete.value = id; // Set the item ID to delete
    showDeleteDialog.value = true; // Show the delete confirmation dialog
};

const handleDelete = async () => {
    try {
        if (!itemIDToDelete.value) return;

        await axios.delete(`${baseentityurl}/${itemIDToDelete.value}`); // Delete the item
        toast.success(`${baseentityname} deleted successfully.`);
        await tableRef.value?.fetchRows(); // Refresh the table data
        showDeleteDialog.value = false; // Hide the delete confirmation dialog
    } catch (error) {
        console.log(`Error deleting ${baseentityname}:`, error);
        toast.error(`Failed to delete ${baseentityname}. Please try again.`);
    }
};
</script>

<template>
    <!-- Page Title -->
    <Head :title="baseentityname" />
    <!-- Layout -->
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-2 rounded-xl p-4">
            <!-- Create Button -->
            <div class="flex items-center gap-2 py-2">
                <div class="ml-auto flex items-center gap-2">
                    <Button class="bg-sky-300" @click="handleOpenDialogForm"> <Plus class="h-4"></Plus> Create {{ baseentityname }} </Button>
                </div>
            </div>

            <!-- Table -->
            <ReusableTable ref="tableRef" :columns="columns" :baseentityname="baseentityname" :baseentityurl="baseentityurl" />

            <!-- Dialog Form -->
            <Dialog v-model:open="showDialogForm">
                <DialogScrollContent class="sm:max-w-[425px]">
                    <DialogHeader>
                        <DialogTitle>{{ mode === 'create' ? 'Create' : 'Update' }} {{ baseentityname }}</DialogTitle>
                    </DialogHeader>
                    <DialogDescription> Use this form to edit the {{ baseentityname }} details. </DialogDescription>
                    <AutoForm class="space-y-6" :form="form" :schema="schema" :field-config="fieldconfig" @submit="onSubmit">
                        <DialogFooter>
                            <Button type="submit" class="bg-green-300">
                                {{ mode === 'create' ? 'Create' : 'Update' }}
                            </Button>
                        </DialogFooter>
                    </AutoForm>
                </DialogScrollContent>
            </Dialog>

            <!-- Delete Confirmation Dialog -->
            <ReusableAlertDialog
                :open="showDeleteDialog"
                :title="`Delete ${baseentityname}`"
                :description="`Are you sure you want to delete this ${baseentityname}? This action cannot be undone.`"
                @confirm="handleDelete"
                @cancel="showDeleteDialog = false"
            />
        </div>
    </AppLayout>
</template>
