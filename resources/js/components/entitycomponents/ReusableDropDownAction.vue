<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { MoreHorizontal } from 'lucide-vue-next';

const props = defineProps({
    rowitem: Object,
    onEdit: Function,
    onDelete: Function,
});

// function copy(id: string) {
//   navigator.clipboard.writeText(id)
// }
const editItem = (id: number) => {
    console.log(id);
    if (props.onEdit) {
        props.onEdit(id);
    } else {
        console.warn('onEdit function is not provided');
    }
};
const deleteItem = (id: number) => {
    if (props.onDelete) {
        props.onDelete(id);
    } else {
        console.warn('onDelete function is not provided');
    }
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="h-8 w-8 p-0">
                <span class="sr-only">Open menu</span>
                <MoreHorizontal class="h-4 w-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuLabel>Actions</DropdownMenuLabel>
            <!-- <DropdownMenuItem @click="copy(category.id)">
        Copy category ID
      </DropdownMenuItem>
      <DropdownMenuSeparator /> -->
            <DropdownMenuItem class="text-yellow-300" @click="editItem(rowitem?.id)">Edit</DropdownMenuItem>
            <DropdownMenuItem class="text-red-300" @click="deleteItem(rowitem?.id)">Delete</DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
