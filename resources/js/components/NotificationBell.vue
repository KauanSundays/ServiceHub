<script setup lang="ts">
import { computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { Bell, MailOpen } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

const page = usePage();
const notifications = computed(() => page.props.auth.notifications ?? []);
const unreadCount = computed(() => notifications.value.length);

const readNotification = (id: string) => {
    router.post(`/notifications/${id}/mark-as-read`, {}, {
        preserveScroll: true,
    });
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <button class="relative group cursor-pointer inline-flex items-center justify-center rounded-md p-2 hover:bg-accent hover:text-accent-foreground">
                <Bell class="h-5 w-5 opacity-80 group-hover:opacity-100" />
                <Badge
                    v-if="unreadCount > 0"
                    variant="destructive"
                    class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full p-0 text-[10px]"
                >
                    {{ unreadCount }}
                </Badge>
            </button>
        </DropdownMenuTrigger>

        <DropdownMenuContent align="end" class="w-80">
            <DropdownMenuLabel>Notificações</DropdownMenuLabel>
            <DropdownMenuSeparator />

            <div v-if="notifications.length === 0" class="p-8 text-center text-sm text-muted-foreground">
                <p>Nenhuma notificação nova.</p>
            </div>

            <div v-else class="max-h-80 overflow-y-auto">
                <DropdownMenuItem
                    v-for="notif in notifications"
                    :key="notif.id"
                    @select="readNotification(notif.id)"
                    class="flex flex-col items-start gap-1 p-4 cursor-pointer focus:bg-accent border-b last:border-0"
                >
                    <div class="flex w-full items-center justify-between">
                        <span class="font-semibold text-sm">{{ notif.data.title }}</span>
                        <MailOpen class="h-3 w-3 opacity-30" />
                    </div>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        {{ notif.data.message }}
                    </p>
                </DropdownMenuItem>
            </div>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
