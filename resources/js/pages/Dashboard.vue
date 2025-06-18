<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid min-h-[40vh] auto-rows-auto gap-4 md:grid-cols-3">
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <Card class="w-[350px]">
                            <CardHeader>
                                <CardTitle>Create project</CardTitle>
                                <CardDescription>Deploy your new project in one-click.</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <form>
                                    <div class="grid w-full items-center gap-4">
                                        <div class="flex flex-col space-y-1.5">
                                            <Label for="name">Name</Label>
                                            <Input id="name" placeholder="Name of your project" />
                                        </div>
                                        <div class="flex flex-col space-y-1.5">
                                            <Label for="framework">Framework</Label>
                                            <Select>
                                                <SelectTrigger id="framework">
                                                    <SelectValue placeholder="Select" />
                                                </SelectTrigger>
                                                <SelectContent position="popper">
                                                    <SelectItem value="nuxt"> Nuxt </SelectItem>
                                                    <SelectItem value="next"> Next.js </SelectItem>
                                                    <SelectItem value="sveltekit"> SvelteKit </SelectItem>
                                                    <SelectItem value="astro"> Astro </SelectItem>
                                                </SelectContent>
                                            </Select>
                                        </div>
                                    </div>
                                </form>
                            </CardContent>
                            <CardFooter class="flex justify-between px-6 pb-6">
                                <Button variant="outline"> Cancel </Button>
                                <Button>Deploy</Button>
                            </CardFooter>
                        </Card>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>
            <div class="relative min-h-[60vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
