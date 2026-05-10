import { Head } from '@inertiajs/react';

export default function Welcome({ auth }) {

    const user = auth?.user;

    return (
        <>
            <Head title="RentHub Mauritius" />

            <div style={{
                fontFamily: 'Arial',
                background: '#f5f5f5',
                minHeight: '100vh'
            }}>

                <nav style={{
                    background: '#111827',
                    color: 'white',
                    padding: '20px 40px',
                    display: 'flex',
                    justifyContent: 'space-between',
                    alignItems: 'center'
                }}>

                    <div style={{
                        fontSize: '26px',
                        fontWeight: 'bold'
                    }}>
                        RentHub
                    </div>

                    <div>

                        <a href="/cars" style={navLink}>
                            Browse Cars
                        </a>

                        {user ? (

                            <a href="/dashboard" style={navLink}>
                                Dashboard
                            </a>

                        ) : (

                            <>
                                <a href="/login" style={navLink}>
                                    Login
                                </a>

                                <a href="/register" style={navLink}>
                                    Register
                                </a>
                            </>

                        )}

                    </div>

                </nav>

                <section style={{
                    padding: '70px 40px',
                    textAlign: 'center',
                    background: 'white'
                }}>

                    <h1 style={{
                        fontSize: '48px',
                        marginBottom: '15px'
                    }}>
                        Rent Cars Easily Across Mauritius
                    </h1>

                    <p style={{
                        fontSize: '20px',
                        color: '#4b5563',
                        maxWidth: '750px',
                        margin: '0 auto 30px'
                    }}>
                        RentHub connects customers with trusted rental companies.
                    </p>

                    <a href="/cars" style={primaryButton}>
                        Browse Available Cars
                    </a>

                </section>

                {user && (

                    <section style={{ padding: '40px' }}>

                        <h2>
                            Welcome back, {user.name}
                        </h2>

                        <div style={grid}>

                            <Card
                                title="Browse Cars"
                                text="Search and book available vehicles."
                                href="/cars"
                            />

                            <Card
                                title="My Bookings"
                                text="View your rental bookings."
                                href="/my-bookings"
                            />

                            <Card
                                title="Notifications"
                                text="Check alerts and updates."
                                href="/notifications"
                            />

                            {user.role === 'rental_company' && (
                                <>
                                    <Card
                                        title="Company Dashboard"
                                        text="View company analytics."
                                        href="/company/dashboard"
                                    />

                                    <Card
                                        title="My Vehicles"
                                        text="Manage listed vehicles."
                                        href="/company/vehicles"
                                    />

                                    <Card
                                        title="Add Vehicle"
                                        text="Create a new listing."
                                        href="/vehicles/create"
                                    />

                                    <Card
                                        title="Company Bookings"
                                        text="Manage rentals."
                                        href="/company/bookings"
                                    />

                                    <Card
                                        title="Fleet Calendar"
                                        text="View booking schedules."
                                        href="/company/calendar"
                                    />
                                </>
                            )}

                            {user.role === 'admin' && (
                                <>
                                    <Card
                                        title="Admin Dashboard"
                                        text="View platform analytics."
                                        href="/admin/dashboard"
                                    />

                                    <Card
                                        title="Company Approvals"
                                        text="Approve companies."
                                        href="/admin/companies"
                                    />
                                </>
                            )}

                        </div>

                    </section>

                )}

            </div>
        </>
    );
}

function Card({ title, text, href }) {

    return (
        <div style={card}>

            <h3>{title}</h3>

            <p style={{ color: '#4b5563' }}>
                {text}
            </p>

            <a href={href} style={secondaryButton}>
                Open
            </a>

        </div>
    );
}

const navLink = {
    color: 'white',
    marginLeft: '20px',
    textDecoration: 'none'
};

const primaryButton = {
    background: '#111827',
    color: 'white',
    padding: '14px 22px',
    borderRadius: '8px',
    textDecoration: 'none',
    display: 'inline-block'
};

const secondaryButton = {
    background: '#111827',
    color: 'white',
    padding: '10px 16px',
    borderRadius: '8px',
    textDecoration: 'none',
    display: 'inline-block',
    marginTop: '10px'
};

const grid = {
    display: 'grid',
    gridTemplateColumns: 'repeat(auto-fit, minmax(260px, 1fr))',
    gap: '20px',
    marginTop: '25px'
};

const card = {
    background: 'white',
    padding: '25px',
    borderRadius: '12px',
    boxShadow: '0 2px 8px rgba(0,0,0,0.08)'
};