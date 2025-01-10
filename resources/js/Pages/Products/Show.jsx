import { Link } from '@inertiajs/react';

export default function Show({ product }) {
    return (
        <div className="product-detail-container">
            <img src={product.image} alt={product.name} className="product-detail-image" />
            <h1 className="product-detail-name">{product.name}</h1>
            <p className="product-detail-description">{product.description}</p>
            <p className="product-detail-price">Price: {product.price}$</p>
            <Link href="/products" className="back-link">
                ย้อนกลับ
            </Link>
        </div>
    );
}
