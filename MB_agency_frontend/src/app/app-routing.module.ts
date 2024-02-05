import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CommonLayoutComponent } from './pages/layouts/common-layout/common-layout.component';
import { CommonLayout_ROUTES } from './shared/routes/common-layout.routes';
import { FullLayoutComponent } from './pages/layouts/full-layout/full-layout.component';
import { FullLayout_ROUTES } from './shared/routes/full-layout.routes';

const routes: Routes = [
  { path: '', pathMatch: 'full', redirectTo: '/login' },
  {
    path: '',
    component: CommonLayoutComponent,
    children: CommonLayout_ROUTES
  },
  {
    path: '',
    component: FullLayoutComponent,
    children: FullLayout_ROUTES
  },

  { path: 'data-semanal-local', loadChildren: () => import('./pages/data-semanal-local/data-semanal-local.module').then(m => m.DataSemanalLocalModule) },




  /*{ path: 'dashboard', loadChildren: () => import('./pages/welcome/welcome.module').then(m => m.WelcomeModule) },
  { path: 'users', loadChildren: () => import('./pages/users/users.module').then(m => m.UsersModule)}*/
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
