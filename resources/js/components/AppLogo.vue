<template>
    <div
        class="flex h-14 w-full items-center border-b border-border/50 px-3 transition-all duration-200 group-data-[collapsible=icon]:justify-center group-data-[collapsible=icon]:px-0"
    >
        <div
            class="flex h-[38px] w-[38px] shrink-0 items-center justify-center rounded-full shadow-sm"
        >
            <img
                src="/logo-small-blue.svg"
                alt="IVAO Colombia Logo"
                class="size-16 object-contain transition-transform duration-200 group-data-[collapsible=icon]:size-6 hover:scale-105"
            />
        </div>

        <div
            class="ml-3 min-w-0 transition-all duration-200 group-data-[collapsible=icon]:hidden"
        >
            <div class="flex items-baseline gap-1.5">
                <span
                    class="text-[19px] leading-none font-semibold tracking-tight text-foreground"
                >
                    IVAO
                </span>
                <span
                    class="text-xs leading-none font-medium text-muted-foreground"
                >
                    CO
                </span>
            </div>
            <span
                class="text-[10px] font-medium tracking-widest text-primary uppercase"
            >
                Portal
            </span>
        </div>
    </div>
</template>
