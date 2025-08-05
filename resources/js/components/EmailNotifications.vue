<script setup lang="ts">

import { Button } from '@/components/ui/button'
import {
  FormControl,
  FormDescription,
  FormField,
  FormItem,
  FormLabel,
} from '@/components/ui/form'
import { Switch } from '@/components/ui/switch'

</script>

<template>
  <form class="w-full space-y-6">
    <div>
      <h3 class="mb-4 text-lg font-medium">
        Email Notifications
      </h3>

      <div class="space-y-4">
        <FormField v-slot="{ value, handleChange }" name="marketing_emails">
          <FormItem class="flex flex-row items-center justify-between rounded-lg border p-4">
            <div class="space-y-0.5">
              <FormLabel class="text-base">
                Marketing emails
              </FormLabel>
              <FormDescription>
                Receive emails about new products, features, and more.
              </FormDescription>
            </div>
            <FormControl>
              <Switch
                :model-value="value"
                @update:model-value="handleChange"
              />
            </FormControl>
          </FormItem>
        </FormField>
        <FormField v-slot="{ value, handleChange }" name="security_emails">
          <FormItem class="flex flex-row items-center justify-between rounded-lg border p-4">
            <div class="space-y-0.5">
              <FormLabel class="text-base">
                Security emails
              </FormLabel>
              <FormDescription>
                Receive emails about your account security.
              </FormDescription>
            </div>
            <FormControl>
              <Switch
                :model-value="value"
                disabled
                aria-readonly
                @update:model-value="handleChange"
              />
            </FormControl>
          </FormItem>
        </FormField>
      </div>
    </div>
    <Button type="submit">
      Submit
    </Button>
  </form>
</template>
