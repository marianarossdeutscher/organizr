import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { DrawerComponent } from '../../components/drawer-component/drawer-component';
import { HeaderComponent, UserProfile } from '../../components/header-component/header-component';
import { TaskColumnComponent } from '../../components/task-column-component/task-column-component';
import { TaskModalComponent } from '../../components/task-modal-component/task-modal-component';
import { TaskService } from '../../services/task-service';
import { Task } from '../../models/task-model.model';

@Component({
  selector: 'app-home-page',
  standalone: true,
  imports: [
    CommonModule,
    DrawerComponent,
    HeaderComponent,
    TaskColumnComponent,
    TaskModalComponent
  ],
  templateUrl: './home-page.html',
  styleUrls: ['./home-page.scss']
})
export class HomePage implements OnInit {
  private taskService = inject(TaskService);
  private router = inject(Router);

  public isSidebarCollapsed = false;
  public user = JSON.parse(localStorage.getItem('user') || '{}');
  public currentUser: UserProfile | null = { name: this.user.username };

  public allTasks: Task[] = [];
  public todoTasks: Task[] = [];
  public inProgressTasks: Task[] = [];
  public completedTasks: Task[] = [];
  private searchTerm: string = '';

  public isModalOpen = false;
  public editingTask: Task | null = null;
  public modalInitialStatus: string = 'To do';

  ngOnInit(): void {
    this.loadTasks();
  }

  loadTasks(): void {
    this.taskService.getTasks().subscribe({
      next: (tasks) => {
        this.allTasks = tasks;
        this.organizeTasks();
      },
      error: (err) => {
        console.error('Erro ao carregar tarefas', err);
        if (err.status === 401) {
          this.logout();
        }
      }
    });
  }

  organizeTasks(): void {
    const sourceTasks = this.searchTerm
      ? this.allTasks.filter(task => task.title.toLowerCase().includes(this.searchTerm.toLowerCase()))
      : this.allTasks;

    this.todoTasks = sourceTasks.filter(t => t.status === 'To do');
    this.inProgressTasks = sourceTasks.filter(t => t.status === 'In progress');
    this.completedTasks = sourceTasks.filter(t => t.status === 'Completed');
  }

  handleSearch(term: string): void {
    this.searchTerm = term;
    this.organizeTasks();
  }

  handleAddTask(status: string): void {
    this.editingTask = null;
    this.modalInitialStatus = status;
    this.isModalOpen = true;
  }

  handleEditTask(task: Task): void {
    this.editingTask = task;
    this.isModalOpen = true;
  }

  handleDeleteTask(taskId: number): void {
    if (confirm('Tem a certeza que quer apagar esta tarefa?')) {
        this.taskService.deleteTask(taskId).subscribe({
            next: () => this.loadTasks(),
            error: (err) => console.error('Erro ao apagar tarefa', err)
        });
    }
  }

  handleSaveTask(taskData: Partial<Task>): void {
    const operation = this.editingTask
      ? this.taskService.updateTask(this.editingTask.id, taskData)
      : this.taskService.createTask(taskData);

    operation.subscribe({
      next: () => {
        this.loadTasks();
        this.closeModal();
      },
      error: (err) => console.error('Erro ao salvar tarefa', err)
    });
  }

  closeModal(): void {
    this.isModalOpen = false;
    this.editingTask = null;
  }

  logout(): void {
    localStorage.removeItem('authToken');
    this.router.navigate(['/login']);
  }
}