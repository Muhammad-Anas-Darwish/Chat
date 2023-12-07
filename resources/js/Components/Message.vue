<script setup>
defineProps({
    color: String,
    message: String,
    time: String,
    classes: String,
});
</script>

<template>
        <p class="rounded-lg text-2xl text-white px-2 break-words max-w-[85%] md:max-w-[70%] w-fit" :class="color , classes">
            {{ message }}
            <span class="text-xs">{{ time }}</span>
        </p>
</template>
