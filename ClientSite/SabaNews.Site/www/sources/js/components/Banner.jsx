import React, { PureComponent } from 'react';
import { BannerType } from '@jsroot/common/constants';
export default class Banner extends PureComponent {
    constructor(props) {
        super(props);

    }

    getBannerData() {
        const { banner, type } = this.props;
        const bannerData = null;
        switch (type) {
            case BannerType.DesktopTop:
                return { src: banner.desktopTopUrl, link: banner.desktopTopNavigationUrl };
            case BannerType.DesktopLeft:
                return { src: banner.desktopLeftUrl, link: banner.desktopLeftNavigationUrl };
            case BannerType.DesktopRight:
                return { src: banner.desktopRightUrl, link: banner.desktopRightNavigationUrl };
            case BannerType.MobileTop:
                return { src: banner.mobileTopUrl, link: banner.mobileTopNavigationUrl };
            case BannerType.MobileCenter:
                return { src: banner.mobileCenterUrl, link: banner.mobileCenterNavigationUrl };
            case BannerType.MobileBottom:
                return { src: banner.mobileBottomUrl, link: banner.mobileBottomNavigationUrl };
            default: return bannerData;
        }
    };

    renderBanner = () => {
        const bannerData = this.getBannerData();
        const { isMobile } = this.props;
        if (bannerData === null || bannerData.src === '') {
            return null;
        };
        const link = bannerData.link === '' ? 'javascripts' : bannerData.link;
        const classDevice = isMobile ? 'show-mobile' : 'show-pc';
        return (
            <div className={`ads-insite ${classDevice}`}>
                <a href={link}>
                    <img src={bannerData.src} className='img-res' />
                </a>
            </div>
        )
    }

    render() {
        const { banner } = this.props;
        if (banner === null) {
            return null;
        }
        return (
            <>
                {this.renderBanner()}
            </>
        )
    }

}