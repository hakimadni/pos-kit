import { Product } from './product';
import { Transaction } from './transaction';

export interface CartItem {
    id: number;
    user_id: number;
    product_id: number;
    quantity: number;
    price: number;
    product: Product;
}
export interface OrderItem {
    id: number;
    user_id: number;
    product_id: number;
    quantity: number;
    price: number;
    product: Product;
    transaction: Transaction;
}
