<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/vue3';
</script>

<template>
    <AuthBase
        title="Create an account"
        description="Enter your details below to create your account"
    >
        <Head title="Register" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        name="name"
                        placeholder="Full name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirm password"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <div class="grid gap-2">
                    <Label for="plan">Select your plan</Label>
                    <div class="grid gap-3">
                        <label
                            class="relative flex cursor-pointer rounded-lg border border-border bg-card p-4 transition-colors hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/5"
                        >
                            <input
                                type="radio"
                                name="plan"
                                value="basic_monthly"
                                required
                                :tabindex="5"
                                class="peer sr-only"
                            />
                            <div class="flex w-full items-center justify-between">
                                <div>
                                    <div class="font-semibold">Basic</div>
                                    <div class="text-sm text-muted-foreground">
                                        Perfect for getting started
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold">€9.99</div>
                                    <div class="text-sm text-muted-foreground">/month</div>
                                </div>
                            </div>
                        </label>

                        <label
                            class="relative flex cursor-pointer rounded-lg border border-border bg-card p-4 transition-colors hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/5"
                        >
                            <input
                                type="radio"
                                name="plan"
                                value="pro_monthly"
                                required
                                :tabindex="6"
                                class="peer sr-only"
                            />
                            <div class="flex w-full items-center justify-between">
                                <div>
                                    <div class="font-semibold">Pro</div>
                                    <div class="text-sm text-muted-foreground">
                                        For growing teams
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold">€19.99</div>
                                    <div class="text-sm text-muted-foreground">/month</div>
                                </div>
                            </div>
                        </label>

                        <label
                            class="relative flex cursor-pointer rounded-lg border border-border bg-card p-4 transition-colors hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/5"
                        >
                            <input
                                type="radio"
                                name="plan"
                                value="enterprise_monthly"
                                required
                                :tabindex="7"
                                class="peer sr-only"
                            />
                            <div class="flex w-full items-center justify-between">
                                <div>
                                    <div class="font-semibold">Enterprise</div>
                                    <div class="text-sm text-muted-foreground">
                                        For large organizations
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold">€49.99</div>
                                    <div class="text-sm text-muted-foreground">/month</div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <InputError :message="errors.plan" />
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full"
                    tabindex="8"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" />
                    Create account
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Already have an account?
                <TextLink
                    :href="login()"
                    class="underline underline-offset-4"
                    :tabindex="9"
                    >Log in</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>
