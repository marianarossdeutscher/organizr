export interface Task {
  id: number;
  userid: number;
  title: string;
  description: string | null;
  endDate: string | null;
  priority: number | null;
  status: 'To do' | 'In progress' | 'Completed';
  shared_with?: any[];
}