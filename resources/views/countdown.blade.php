<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>IVAO Colombia — Próximamente</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    animation: {
                        'pulse-slow': 'pulse 4s ease-in-out infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen bg-[#050b14] text-white font-sans overflow-hidden">

    <!-- Background -->
    <div class="fixed inset-0 pointer-events-none">

        <!-- Gradient -->
        <div class="absolute inset-0
            bg-[radial-gradient(circle_at_50%_30%,rgba(30,136,229,0.16),transparent_35%),
                radial-gradient(circle_at_80%_80%,rgba(0,188,212,0.08),transparent_30%)]">
        </div>

        <!-- Grid -->
        <div class="absolute inset-0 opacity-[0.035]" style="
                background-image:
                    linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px);
                background-size: 50px 50px;
            "></div>

        <!-- Glow -->
        <div class="absolute top-1/2 left-1/2
            -translate-x-1/2 -translate-y-1/2
            w-[600px] h-[600px]
            bg-blue-500/10 blur-[120px] rounded-full">
        </div>
    </div>


    <!-- Main -->
    <main class="relative z-10 min-h-screen flex items-center justify-center px-6">

        <div class="w-full max-w-5xl text-center">

            <!-- Logo -->
            <div class="flex justify-center mb-10 animate-float">

                <div class="
                    w-24 h-24
                    rounded-3xl
                    border border-white/10
                    bg-white/[0.04]
                    backdrop-blur-xl
                    flex items-center justify-center
                    shadow-2xl shadow-blue-500/10
                ">
                    <img src="{{ asset(" theme-1/images/logoivao.png")}}">
                </div>

            </div>


            <!-- Badge -->
            <div class="
                inline-flex items-center gap-2
                px-4 py-2
                rounded-full
                border border-blue-400/20
                bg-blue-400/[0.06]
                text-blue-300
                text-sm font-medium
                mb-7
            ">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-400"></span>
                </span>

                NUEVA WEB DIVISIONAL
            </div>


            <!-- Heading -->
            <h1 class="
                text-5xl sm:text-6xl md:text-7xl
                font-extrabold
                tracking-tight
                leading-[0.95]
            ">
                IVAO
                <span class="text-transparent bg-clip-text
                    bg-gradient-to-r from-amber-300 via-blue-400 to-red-500">
                    Colombia
                </span>
            </h1>


            <p class="
                mt-7
                max-w-2xl
                mx-auto
                text-base sm:text-lg
                text-slate-400
                leading-relaxed
            ">
                Estamos preparando una nueva experiencia para nuestra
                comunidad de aviación virtual.
                <br class="hidden sm:block">
                <span class="text-slate-300">
                    La nueva web divisional está a punto de despegar.
                </span>
            </p>


            <!-- Countdown -->
            <div id="countdown" class="
                    mt-12
                    grid grid-cols-2 sm:grid-cols-4
                    gap-3 sm:gap-5
                    max-w-3xl
                    mx-auto
                ">

                <!-- Days -->
                <div class="
                    group
                    rounded-2xl
                    border border-white/10
                    bg-white/[0.035]
                    backdrop-blur-xl
                    p-5 sm:p-7
                    transition
                    hover:border-blue-400/30
                    hover:bg-blue-400/[0.05]
                ">
                    <div id="days" class="text-4xl sm:text-5xl font-bold tracking-tight">
                        00
                    </div>

                    <div class="
                        mt-2
                        text-xs
                        uppercase
                        tracking-[0.2em]
                        text-slate-500
                    ">
                        Días
                    </div>
                </div>


                <!-- Hours -->
                <div class="
                    group
                    rounded-2xl
                    border border-white/10
                    bg-white/[0.035]
                    backdrop-blur-xl
                    p-5 sm:p-7
                    transition
                    hover:border-blue-400/30
                    hover:bg-blue-400/[0.05]
                ">
                    <div id="hours" class="text-4xl sm:text-5xl font-bold tracking-tight">
                        00
                    </div>

                    <div class="
                        mt-2
                        text-xs
                        uppercase
                        tracking-[0.2em]
                        text-slate-500
                    ">
                        Horas
                    </div>
                </div>


                <!-- Minutes -->
                <div class="
                    group
                    rounded-2xl
                    border border-white/10
                    bg-white/[0.035]
                    backdrop-blur-xl
                    p-5 sm:p-7
                    transition
                    hover:border-blue-400/30
                    hover:bg-blue-400/[0.05]
                ">
                    <div id="minutes" class="text-4xl sm:text-5xl font-bold tracking-tight">
                        00
                    </div>

                    <div class="
                        mt-2
                        text-xs
                        uppercase
                        tracking-[0.2em]
                        text-slate-500
                    ">
                        Minutos
                    </div>
                </div>


                <!-- Seconds -->
                <div class="
                    group
                    rounded-2xl
                    border border-white/10
                    bg-white/[0.035]
                    backdrop-blur-xl
                    p-5 sm:p-7
                    transition
                    hover:border-blue-400/30
                    hover:bg-blue-400/[0.05]
                ">
                    <div id="seconds" class="text-4xl sm:text-5xl font-bold tracking-tight text-blue-300">
                        00
                    </div>

                    <div class="
                        mt-2
                        text-xs
                        uppercase
                        tracking-[0.2em]
                        text-slate-500
                    ">
                        Segundos
                    </div>
                </div>

            </div>


            <!-- Launch date -->
            <div class="mt-8 text-sm text-slate-500">
                Lanzamiento:
                <span class="text-slate-300 font-medium">
                    16 de agosto de 2026
                </span>
            </div>


            <!-- Footer -->
            <div class="
                mt-16
                pt-6
                border-t border-white/[0.06]
                text-xs text-slate-600
            ">
                IVAO Colombia · División CO · Virtual Aviation Network
            </div>

        </div>

    </main>


    <!-- Countdown JS -->
    <script>
        // ==========================================
        // FECHA DE LANZAMIENTO
        // ==========================================

        // 16 de agosto de 2026 - 00:00 Colombia
        const launchDate = new Date("2026-08-16T00:00:00-05:00");


        function updateCountdown() {

            const now = new Date();

            const difference = launchDate - now;


            if (difference <= 0) {

                document.getElementById("countdown").innerHTML = `
                    <div class="
                        col-span-2 sm:col-span-4
                        py-8
                        text-3xl
                        font-bold
                        text-blue-300
                    ">
                        ¡La nueva web ya está disponible!
                    </div>
                `;

                return;
            }


            const days = Math.floor(
                difference / (1000 * 60 * 60 * 24)
            );

            const hours = Math.floor(
                (difference / (1000 * 60 * 60)) % 24
            );

            const minutes = Math.floor(
                (difference / (1000 * 60)) % 60
            );

            const seconds = Math.floor(
                (difference / 1000) % 60
            );


            document.getElementById("days").textContent =
                String(days).padStart(2, "0");

            document.getElementById("hours").textContent =
                String(hours).padStart(2, "0");

            document.getElementById("minutes").textContent =
                String(minutes).padStart(2, "0");

            document.getElementById("seconds").textContent =
                String(seconds).padStart(2, "0");
        }


        updateCountdown();

        setInterval(updateCountdown, 1000);

    </script>

</body>

</html>