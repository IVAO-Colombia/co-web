<script setup lang="ts">
withDefaults(
    defineProps<{
        title: string;
        range: string;
        position: string;
        localTime: string;
        date: string;
        backgroundImage?: string;
    }>(),
    {
        backgroundImage: '/img/16x9_image.jpg',
    },
);
</script>

<template>
    <!--
        Fixed at 1200×675px (16:9). Uses inline styles so html-to-image captures
        them correctly regardless of Tailwind class resolution.
    -->
    <div
        style="
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            width: 1200px;
            height: 675px;
            background-color: #0d2c99;
            overflow: hidden;
            font-family: 'Poppins', 'Nunito Sans', sans-serif;
            color: #ffffff;
        "
    >
        <!-- Background photo -->
        <!-- <img
            :src="backgroundImage"
            alt=""
            style="
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
                opacity: 0.35;
            "
        /> -->

        <!-- Dark gradient overlay -->
        <div
            style="
                position: absolute;
                inset: 0;
                background: linear-gradient(
                    135deg,
                    rgba(0, 0, 0, 0.85) 0%,
                    rgba(0, 0, 0, 0.4) 50%,
                    rgba(0, 0, 0, 0.75) 100%
                );
            "
        />

        <!-- Faded IVAO watermark -->
        <div
            style="
                position: absolute;
                right: -30px;
                bottom: 0;
                transform: translateY(16%) translateX(2%);
                font-size: 470px;
                font-weight: 900;
                color: rgba(255, 255, 255, 0.2);
                letter-spacing: 25px;
                line-height: 1;
                user-select: none;
                white-space: nowrap;
            "
        >
            IVAO
        </div>

        <!-- Top-right logo -->
        <div
            style="
                position: absolute;
                top: 36px;
                right: 48px;
                display: flex;
                align-items: center;
                gap: 10px;
            "
        >
            <img
                src="/logo-white.png"
                alt="IVAO CO"
                style="height: 44px; width: auto; object-fit: contain"
            />
        </div>

        <!-- Main content area -->
        <div
            style="
                position: absolute;
                left: 72px;
                top: 0;
                bottom: 0;
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 0;
            "
        >
            <!-- Range label (e.g. "Entrenamiento") -->
            <div
                style="
                    font-size: 32px;
                    font-weight: 400;
                    color: rgba(255, 255, 255, 0.72);
                    letter-spacing: 0.03em;
                    margin-bottom: 6px;
                "
            >
                {{ range }}
            </div>

            <!-- Title row: star + big title -->
            <div
                style="
                    display: flex;
                    align-items: center;
                    gap: 20px;
                    margin-bottom: 40px;
                "
            >
                <!-- Gold star -->
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="56"
                    height="56"
                    viewBox="0 0 24 24"
                    fill="#f5c518"
                    style="flex-shrink: 0; margin-bottom: 4px"
                >
                    <polygon
                        points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                    />
                </svg>

                <!-- Big title -->
                <span
                    style="
                        font-size: 140px;
                        font-weight: 900;
                        line-height: 1;
                        letter-spacing: -4px;
                        color: #ffffff;
                        white-space: nowrap;
                    "
                >
                    {{ title }}
                </span>
            </div>

            <!-- Info rows -->
            <div style="display: flex; flex-direction: column; gap: 14px">
                <!-- Position -->
                <div style="display: flex; align-items: center; gap: 14px">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="22"
                        height="22"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="rgba(255,255,255,0.7)"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path
                            d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"
                        />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    <span
                        style="
                            font-size: 32px;
                            font-weight: 600;
                            color: rgba(255, 255, 255, 0.85);
                            letter-spacing: 0.04em;
                        "
                    >
                        {{ position }}
                    </span>
                </div>

                <!-- Local time -->
                <div style="display: flex; align-items: center; gap: 14px">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="22"
                        height="22"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="rgba(255,255,255,0.7)"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    <span
                        style="
                            font-size: 32px;
                            font-weight: 600;
                            color: rgba(255, 255, 255, 0.85);
                            letter-spacing: 0.04em;
                        "
                    >
                        {{ localTime }}
                    </span>
                </div>

                <!-- Date -->
                <div style="display: flex; align-items: center; gap: 14px">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="22"
                        height="22"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="rgba(255,255,255,0.7)"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <rect
                            width="18"
                            height="18"
                            x="3"
                            y="4"
                            rx="2"
                            ry="2"
                        />
                        <line x1="16" x2="16" y1="2" y2="6" />
                        <line x1="8" x2="8" y1="2" y2="6" />
                        <line x1="3" x2="21" y1="10" y2="10" />
                        <path d="m9 16 2 2 4-4" />
                    </svg>
                    <span
                        style="
                            font-size: 32px;
                            font-weight: 600;
                            color: rgba(255, 255, 255, 0.85);
                            letter-spacing: 0.04em;
                        "
                    >
                        {{ date }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
