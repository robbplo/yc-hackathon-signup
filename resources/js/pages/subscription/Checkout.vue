<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { create } from '@/routes/subscription';
import { Form, Head } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    plan: string;
}

const props = defineProps<Props>();

const planDetails = computed(() => {
    const plans = {
        basic_monthly: {
            name: 'Basic',
            price: '€9.99',
            description: 'Perfect for getting started',
        },
        pro_monthly: {
            name: 'Pro',
            price: '€19.99',
            description: 'For growing teams',
        },
        enterprise_monthly: {
            name: 'Enterprise',
            price: '€49.99',
            description: 'For large organizations',
        },
    };

    return plans[props.plan as keyof typeof plans] || plans.basic_monthly;
});
</script>

<template>
    <AuthBase
        title="Complete your subscription"
        description="You're almost there! Complete your payment to activate your subscription"
    >
        <Head title="Complete Subscription" />

        <div class="flex flex-col gap-6">
            <div class="rounded-lg border border-border bg-card p-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">
                            {{ planDetails.name }} Plan
                        </h3>
                        <p class="text-sm text-muted-foreground">
                            {{ planDetails.description }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ planDetails.price }}</div>
                        <div class="text-sm text-muted-foreground">/month</div>
                    </div>
                </div>

                <div class="border-t border-border pt-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-muted-foreground">Billing cycle</span>
                        <span class="font-medium">Monthly</span>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-sm">
                        <span class="text-muted-foreground">First payment</span>
                        <span class="font-medium">{{ planDetails.price }}</span>
                    </div>
                </div>
            </div>

            <Form v-bind="create.form()" v-slot="{ processing }" class="flex flex-col gap-4">
                <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900
                    dark:border-blue-900 dark:bg-blue-950 dark:text-blue-100">
                    You will be redirected to Mollie to complete your payment securely.
                </div>

                <Button type="submit" class="w-full" :disabled="processing">
                    <Spinner v-if="processing" />
                    Continue to payment
                </Button>
            </Form>

            <p class="text-center text-xs text-muted-foreground">
                By continuing, you agree to our terms of service and privacy policy. Your
                subscription will automatically renew each month.
            </p>
        </div>
    </AuthBase>
</template>
