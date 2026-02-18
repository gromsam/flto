import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      redirect: '/shops/1/growth/telegram',
    },
    {
      path: '/shops/:shopId/growth/telegram',
      name: 'telegram',
      component: () => import('../pages/TelegramIntegrationPage.vue'),
      props: true,
    },
  ],
})

export default router
