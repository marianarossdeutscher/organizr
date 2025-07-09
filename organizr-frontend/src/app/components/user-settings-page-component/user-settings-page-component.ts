import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth-service'; 
import { UserService } from '../../services/user-service';

@Component({
  selector: 'app-user-settings-page',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './user-settings-page-component.html',
  styleUrls: ['./user-settings-page-component.scss']
})
export class UserSettingsPageComponent implements OnInit {
  private fb = inject(FormBuilder);
  private router = inject(Router);
  private authService = inject(AuthService);
  private userService = inject(UserService);
  private userInfo: any;

  settingsForm!: FormGroup;

  constructor() {
    this.settingsForm = this.fb.group({
      username: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]]
    });
  }

  ngOnInit(): void {    
    const userString = localStorage.getItem('user');
    
    if (userString) {
      try {
        this.userInfo = JSON.parse(userString);
      } catch (e) {
        console.error("Erro ao ler dados do usuário do localStorage", e);
      }
      this.settingsForm.get('username')?.setValue(this.userInfo?.username || '');
      this.settingsForm.get('email')?.setValue(this.userInfo?.email || '');
    }
  }

  onSubmit(): void {
    if (this.settingsForm.invalid || !this.userInfo) {
      return;
    }

    console.log('A salvar dados:', this.settingsForm.value);

 
    this.userService.updateUser(this.userInfo.id, this.settingsForm.value).subscribe({
      next: (updatedUser) => {
        const userString = localStorage.getItem('user');
        if (userString) {
          const user = JSON.parse(userString);
          const newUser = { ...user, ...updatedUser };
          localStorage.setItem('user', JSON.stringify(newUser));
        }

        alert('Perfil atualizado com sucesso!');
        this.router.navigate(['/home']);
      },
      error: (error) => {
        console.error('Erro ao atualizar configurações:', error);
        alert('Ocorreu um erro ao atualizar o perfil.');
      }
    });
  }

  goBack(): void {
    this.router.navigate(['/home']);
  }
}