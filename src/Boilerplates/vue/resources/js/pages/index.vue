<template>
  <main class="app flex items-center justify-between">
    <div class="home-page z-10 flex-1">
      <section>
        <div class="text-white bg-black/60 px-20 py-10 rounded-lg shadow-lg">
          <!-- Main Message -->
          <div class="text-center">
            <p class="text-4xl font-bold text-green-500 mb-10">🎉 Congratulations!</p>
            <p class="mt-2 text-lg text-white">
              Your brand-new, shiny <span class="font-semibold">Laravel Stack</span>
              is installed and ready to go.
            </p>
            <p class="mt-4 text-sm text-gray-100">
              Thank you for choosing <strong>Laravel Stack Installer</strong>.
              We’re excited to be part of your development journey. 🚀
            </p>
            <p class="mt-4 text-sm text-gray-100">
              As a quick start, we’ve added a
              <code class="bg-gray-700 px-2 py-1 rounded">pages</code> directory
              in your <code class="bg-gray-700 px-2 py-1 rounded">/resources/js/</code>
              folder. Navigate there to get started with your project!
            </p>
          </div>

          <!-- Action Cards -->
          <div class="flex flex-wrap justify-center gap-6 mt-20">
            <!-- GitHub Card -->
            <div class="z-10 card flex flex-col items-center bg-white p-6 rounded-lg shadow-lg max-w-sm">
              <h3 class="text-xl font-semibold text-blue-500">⭐ Star Us on GitHub</h3>
              <p class="mt-2 text-sm text-gray-600 text-center">
                Love the stack? Support us by starring our repository and
                contributing to its growth.
              </p>
              <a href="https://github.com/ngfw/laravel-stack" target="_blank" rel="noopener noreferrer"
                class="z-20 mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Visit GitHub
              </a>
            </div>

            <!-- Quick Start Card -->
            <div class="z-10 card flex flex-col items-center bg-white p-6 rounded-lg shadow-lg max-w-sm">
              <h3 class="text-xl font-semibold text-green-500">🚀 Quick Start</h3>
              <p class="mt-2 text-sm text-gray-600 text-center">
                Ready to dive in? <br />Head to your
                <code class="bg-gray-200 px-2 py-1 rounded">/resources/js/pages/</code>
                directory and start building something amazing!
              </p>
            </div>
          </div>
        </div>
        <ul class="circles">
          <li v-for="i in 10" :key="i"></li>
        </ul>
      </section>
    </div>
  </main>
</template>

<script>
import { confetti } from "@tsparticles/confetti";

export default {
  name: 'Home',
  mounted() {
    this.launchConfetti();
  },
  methods: {
    async launchConfetti() {
      await confetti({
        particles: 100,
        angle: 90,
        spread: 50,
        origin: { x: 0.5, y: 0.5 },
      });
    },
  },
};
</script>

<style>
@keyframes animate {
  0% {
    transform: translateY(0) rotate(0deg);
    opacity: 1;
    border-radius: 20%;
  }

  100% {
    transform: translateY(-1000px) rotate(720deg);
    opacity: 0;
    border-radius: 80%;
  }
}
@keyframes scale1{
    0% {
        width: 140%;
        height: 140%;
        top: -20%; 
        left: -20%;
        border-radius: 100%;
    }
    100% {
        width: 200%;
        height: 200%;
        top: -50%; 
        left: -50%;
        border-radius: 90%;
    }
}
@keyframes scale2{
    0% {
        width: 130%;
        height: 130%; 
        top: -15%; 
        left: -15%;
        border-radius: 100%;
    }
    100% {
        width: 170%;
        height: 170%;
        top: -35%; 
        left: -35%;
        border-radius: 90%;
    }
}
.app {
  width: 100%;
  height: 100%;

  .home-page {
    @apply font-bold text-center;

    section {
      @apply relative flex justify-center items-center bg-gray-900 min-h-screen overflow-hidden;

      .content {
        @apply flex flex-col md:flex-row justify-center items-center gap-8 bg-white bg-opacity-30 backdrop-blur-lg rounded-xl p-6 shadow-lg;
        width: min(900px, 100%);
      }

      .info {
        @apply flex flex-col justify-center items-center max-w-md px-8 text-justify;

        p {
          @apply text-white font-medium text-base mb-5 leading-relaxed;
        }
      }
    }

    .circles {
        @apply absolute inset-0 w-full h-full overflow-hidden opacity-50;
        list-style: none;
        padding: 0;
        margin: 0;

        li::before,
        li::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background-color: transparent;
        }
        li::before {
            @apply border-2 border-red-800/80;
            top: -30%; 
            left: -30%; 
            animation: scale1 25s linear infinite;
          }
          
        li::after {
            @apply border-2 border-red-500;
            top: -30%; 
            left: -30%;
            animation: scale2 25s linear infinite;
          }
        li {
            @apply absolute block list-none rounded-full border-2 border-red-400;
            z-index: 0;
            width: 20px;
            height: 20px;
            background: url('https://laravel.com/assets/img/welcome/background.svg');
            background-repeat: no-repeat;
            animation: animate 25s linear infinite;
            bottom: -180px;
            &:nth-child(1) {
                left: 25%;
                @apply w-20 h-20;
                animation-delay: 0s;
            }
            &:nth-child(2) {
                left: 10%;
                @apply w-5 h-5;
                animation-delay: 2s;
                animation-duration: 12s;
            }
            &:nth-child(3) {
                left: 70%;
                @apply w-5 h-5;
                animation-delay: 4s;
            }
            &:nth-child(4) {
                left: 40%;
                @apply w-16 h-16;
                animation-delay: 0s;
                animation-duration: 18s;
            }
            &:nth-child(5) {
                left: 65%;
                @apply w-5 h-5;
                animation-delay: 0s;
            }
            &:nth-child(6) {
                left: 75%;
                @apply w-28 h-28;
                animation-delay: 3s;
            }
            &:nth-child(7) {
                left: 35%;
                @apply w-36 h-36;
                animation-delay: 7s;
            }
            &:nth-child(8) {
                left: 50%;
                @apply w-6 h-6;
                animation-delay: 15s;
                animation-duration: 45s;
            }
            &:nth-child(9) {
                left: 20%;
                @apply w-4 h-4;
                animation-delay: 2s;
                animation-duration: 35s;
            }
            &:nth-child(10) {
                left: 85%;
                @apply w-36 h-36;
                animation-delay: 0s;
                animation-duration: 11s;
            }
        }
    }
  }
  
}
</style>
