select* from Service

/* =========================================================
   SERVICES (ServiceType = 0)
   ========================================================= */


ALTER TABLE Service
ADD Image VARCHAR(255);

INSERT INTO Service
(
    ServiceType,
    Category,
    ServiceName,
    Description,
    DurationMinutes,
    Price,
    IsActive
)
VALUES

-- ==========================
-- HAIR SERVICES
-- ==========================
(0,N'Hair',N'Cut & Wash',
N'Professional haircut combined with relaxing hair wash.',
45,180000,1),

(0,N'Hair',N'Hair Coloring',
N'Premium hair coloring with high-quality products.',
180,650000,1),

(0,N'Hair',N'Hair Perm',
N'Natural curl styling for long-lasting volume.',
180,700000,1),

(0,N'Hair',N'Hair Straightening',
N'Smooth and silky hair straightening treatment.',
180,700000,1),

(0,N'Hair',N'Hair Restoration',
N'Intensive treatment to repair damaged and dry hair.',
90,450000,1),


(0, N'Hair', N'Hair Extension',
N'Professional hair extension using premium natural hair for longer and fuller hairstyle.',
180, 1800000,1,
'hair_noi.jpg'
),



-- ==========================
-- SKIN SERVICES
-- ==========================
(0,N'Skin',N'Advanced Acne Treatment',
N'Professional 17-step acne treatment for deep cleansing and skin recovery.',
90,550000,1),

(0,N'Skin',N'Glowing Peel',
N'Chemical peel to brighten skin and reduce acne marks.',
60,500000,1),

(0,N'Skin',N'Deep Clean Skin',
N'Deep cleansing, enzyme treatment, exfoliation, facial mask, E-Light and hydrating ampoule infusion.',
60,200000,1),

(0,N'Skin',N'Fractional CO2 Laser',
N'Laser treatment for acne scars and skin resurfacing.',
90,1200000,1),

(0,N'Skin',N'Meso Ultra Exosome',
N'Advanced exosome mesotherapy for skin regeneration and anti-aging.',
75,1500000,1),

(0, N'Skin', N'CO2 Detox Therapy', N'Deep skin detox using CO2 technology to purify pores and improve skin vitality.',
60, 450000, 1, 'skin_detox.jpg'
);


-- ==========================
-- MASSAGE SERVICES
-- ==========================
(0,N'Massage',N'Relaxing Body Massage',
N'Full-body massage with essential oils to relax muscles and improve blood circulation.',
60,450000,1),

(0,N'Massage',N'Neck & Shoulder Therapy',
N'Therapeutic massage focusing on neck, shoulders and upper back pain relief.',
45,300000,1),

(0,N'Massage',N'Herbal Hair Wash',
N'Traditional herbal hair wash combined with relaxing head massage.',
60,250000,1);
GO



/* =========================================================
   PRODUCTS (ServiceType = 1)
   ========================================================= */

INSERT INTO Service
(
    ServiceType,
    Category,
    ServiceName,
    Description,
    DurationMinutes,
    Price,
    IsActive
)
VALUES

-- ==========================
-- HAIR PRODUCTS
-- ==========================
(1,N'Hair',N'Professional Shampoo',
N'Salon-quality shampoo for daily hair care.',
0,280000,1),

(1,N'Hair',N'Hair Repair Serum',
N'Serum that repairs damaged hair and split ends.',
0,350000,1),

(1,N'Hair',N'Professional Conditioner',
N'Nourishing conditioner for soft and healthy hair.',
0,300000,1),




-- ==========================
-- SKIN PRODUCTS
-- ==========================
(1,N'Skin',N'Facial Cleanser',
N'Gentle cleanser for deep facial cleansing.',
0,250000,1),

(1,N'Skin',N'Vitamin C Serum',
N'Brightening serum with Vitamin C.',
0,650000,1),

(1,N'Skin',N'Hydrating Cream',
N'Deep moisturizing cream for healthy skin.',
0,450000,1),



-- ==========================
-- MASSAGE PRODUCTS
-- ==========================
(1,N'Massage',N'Massage Oil',
N'Professional massage oil for body relaxation.',
0,350000,1),

(1,N'Massage',N'Essential Oil',
N'Aromatherapy essential oil for massage sessions.',
0,480000,1),

(1,N'Massage',N'Body Lotion',
N'Moisturizing body lotion after massage treatment.',
0,320000,1);
GO

-- inser hair SV
UPDATE Service
SET Image='hair_cut.jpg'
WHERE ServiceName='Cut & Wash';

UPDATE Service
SET Image='hair_nhuom.jpg'
WHERE ServiceName='Hair Coloring';

UPDATE Service
SET Image='hair_uon.jpg'
WHERE ServiceName='Hair Perm';

UPDATE Service
SET Image='hair_duoi.jpg'
WHERE ServiceName='Hair Straightening';

UPDATE Service
SET Image='hair_phuc_hoi.jpg'
WHERE ServiceName='Hair Restoration';

-- hair sp
UPDATE Service
SET Image='hair_daugoi.jpg'
WHERE ServiceName='Professional Shampoo';

UPDATE Service
SET Image='hair_dauxa.jpg'
WHERE ServiceName='Professional Conditioner';

UPDATE Service
SET Image='hair_oil.jpg'
WHERE ServiceName='Hair Repair Serum';


-- skin sv
UPDATE Service
SET Image='skin_acne.jpg'
WHERE ServiceName='Advanced Acne Treatment';

UPDATE Service
SET Image='skin_glowing.jpg'
WHERE ServiceName='Glowing Peel';

UPDATE Service
SET Image='skin_deep.jpg'
WHERE ServiceName='Deep Clean Skin';

UPDATE Service
SET Image='skin_laser.jpg'
WHERE ServiceName='Fractional CO2 Laser';

UPDATE Service
SET Image='skin_meso.jpg'
WHERE ServiceName='Meso Ultra Exosome';

-- skin SP
UPDATE Service
SET Image='skin_SRM.jpg'
WHERE ServiceName='Facial Cleanser';

UPDATE Service
SET Image='skin_serum.jpg'
WHERE ServiceName='Vitamin C Serum';

UPDATE Service
SET Image='skin_kem.jpg'
WHERE ServiceName='Hydrating Cream';

-- massage sv
UPDATE Service
SET Image='mx-body.jpg'
WHERE ServiceName='Relaxing Body Massage';

UPDATE Service
SET Image='mx-neck.jpg'
WHERE ServiceName='Neck & Shoulder Therapy';

UPDATE Service
SET Image='mx-goi.jpg'
WHERE ServiceName='Herbal Hair Wash';


-- Massage product
UPDATE Service
SET Image='mx-oil1.jpg'
WHERE ServiceName='Massage Oil';

UPDATE Service
SET Image='mx-oil2.jpg'
WHERE ServiceName='Essential Oil';

UPDATE Service
SET Image='mx-lotion.jpg'
WHERE ServiceName='Body Lotion';


SELECT
    ServiceID,
    ServiceType,
    Category,
    ServiceName,
    Image
FROM Service
ORDER BY ServiceType, Category, ServiceID;

INSERT INTO Service
(
    ServiceType,
    Category,
    ServiceName,
    Description,
    DurationMinutes,
    Price,
    IsActive,
    Image
)
VALUES

(
0,
N'Hair',
N'Hair Extension',
N'Professional hair extension using premium natural hair for longer and fuller hairstyle.',
180,
1800000,
1,
'hair_noi.jpg'
),

(
0,
N'Skin',
N'CO2 Detox Therapy',
N'Deep skin detox using CO2 technology to purify pores and improve skin vitality.',
60,
450000,
1,
'skin_detox.jpg'
);
GO
