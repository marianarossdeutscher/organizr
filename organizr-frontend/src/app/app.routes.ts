import { Routes } from '@angular/router';
import { LoginPage } from './pages/login-page/login-page';
import { SignupPage } from './pages/signup-page/signup-page';
import { HomePage } from './pages/home-page/home-page';
import { UserSettingsPageComponent } from './components/user-settings-page-component/user-settings-page-component';

export const routes: Routes = [
     { path: '', redirectTo: 'login', pathMatch: 'full'},
     {
        path: 'login',
        component: LoginPage,
        children: []
     },
     {
        path: 'signup',
        component: SignupPage,
        children: []
     },
     {
        path: 'home',
        component: HomePage,
        children: []
     },
     {
        path: 'settings',
        component: UserSettingsPageComponent,
        children: []
     }
];