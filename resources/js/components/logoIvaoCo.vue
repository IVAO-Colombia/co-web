<script setup lang="ts">
import { computed } from 'vue'
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'


defineOptions({
	inheritAttrs: false,
})

type SizeValue = number | string

const props = withDefaults(defineProps<{
	src?: string
	alt?: string
	width?: SizeValue
	height?: SizeValue
	showText?: boolean
	titleText?: string
	countryText?: string
	class?: HTMLAttributes['class']
	imgClass?: HTMLAttributes['class']
}>(), {
	src: '',
	alt: 'IVAO Colombia',
	width: 'auto',
	height: 42,
	showText: true,
	titleText: 'IVAO',
	countryText: 'COLOMBIA',
})

function toCssSize(value?: SizeValue): string | undefined {
	if (value === undefined || value === null || value === '') {
		return undefined
	}

	return typeof value === 'number' ? `${value}px` : value
}

const containerStyle = computed(() => {
	return {
		width: toCssSize(props.width),
		height: toCssSize(props.height),
	}
})
</script>

<template>
	<span
		:class="cn('inline-flex shrink-0 items-center gap-2', props.class)"
		:style="containerStyle"
		v-bind="$attrs"
	>
		<img
			:src="props.src"
			:alt="props.alt"
			:class="cn(
				'block h-full w-auto shrink-0 object-contain',
				props.imgClass,
			)"
			decoding="async"
			draggable="false"
		>

		<span
			v-if="props.showText"
			class="flex flex-col leading-none"
		>
			<span class="text-[0.88rem] font-black tracking-tight text-[#0D2C99] sm:text-[1.75rem] dark:text-white">
				{{ props.titleText }}
			</span>
			<span class="text-[0.52rem] font-semibold tracking-[0.22em] text-[#3C55AC] uppercase sm:text-[0.65rem] dark:text-white">
				{{ props.countryText }}
			</span>
		</span>
	</span>
</template>
