<script setup lang="ts">
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { dashboard } from '@/routes';
import { Head, router } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

interface Props {
    message: string;
}

defineProps<Props>();

const checkCount = ref(0);
const maxChecks = 20; // Check for up to 20 seconds
let intervalId: number | undefined;

const checkSubscriptionStatus = () => {
    checkCount.value++;

    // Reload the current page to check subscription status
    // The controller will redirect to dashboard if subscription is active
    router.reload({
        preserveScroll: true,
        onSuccess: () => {
            if (checkCount.value >= maxChecks) {
                // After max checks, redirect to support or show error
                window.location.href = dashboard.url();
            }
        },
    });
};

onMounted(() => {
    // Check every 2 seconds
    intervalId = window.setInterval(checkSubscriptionStatus, 2000);

    // Also check immediately
    setTimeout(checkSubscriptionStatus, 500);
});

onUnmounted(() => {
    if (intervalId) {
        clearInterval(intervalId);
    }
});
</script>

<template>
    <AuthBase
        title="Processing your payment"
        description="Please wait while we confirm your subscription"
    >
        <Head title="Processing Payment" />

        <div class="flex flex-col items-center gap-6 py-8">
            <Spinner class="size-12" />

            <div class="text-center">
                <p class="text-lg font-medium">{{ message }}</p>
                <p class="mt-2 text-sm text-muted-foreground">
                    This usually takes just a few seconds.
                </p>
            </div>

            <div class="mt-4 rounded-lg border border-border bg-muted/50 p-4 text-center text-sm">
                <p class="text-muted-foreground">
                    If this takes longer than expected, you will be automatically redirected to
                    your dashboard. Your subscription will be activated shortly.
                </p>
            </div>
        </div>
    </AuthBase>
</template>
